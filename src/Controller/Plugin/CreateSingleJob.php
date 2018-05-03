<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Controller\Plugin;

use Core\Entity\Collection\ArrayCollection;
use Jobs\Entity\AtsMode;
use Jobs\Entity\Location;
use Jobs\Entity\Status;
use Orders\Entity\InvoiceAddress;
use Orders\Entity\OrderInterface;
use Orders\Entity\Product;
use Orders\Entity\Snapshot\Job\Builder;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class CreateSingleJob extends AbstractPlugin
{

    private $jobRepository;
    private $orderRepository;
    private $orderOptions;
    private $mailer;

    public function __construct(
        \Jobs\Repository\Job $jobRepository,
        \Orders\Repository\Orders $orderRespository,
        \Orders\Options\ModuleOptions $orderOptions,
        \Core\Mail\MailService $mailer
    ) {
        $this->jobRepository = $jobRepository;
        $this->orderRepository = $orderRespository;
        $this->orderOptions = $orderOptions;
        $this->mailer  = $mailer;
    }

    public function __invoke($values)
    {
        $job   = $this->createJob($values);
        $order = $this->createOrder($job, $values);
        $this->sendMails($job, $order, $values);
    }


    private function createJob($values)
    {
        /* @var \Jobs\Entity\Job $job */
        $job = $this->jobRepository->create();
        $job->setCompany($values['invoiceAddress']['company']);
        $job->setLink($values['uri']);
        $job->setTitle($values['title']);
        $job->setStatus(Status::CREATED);
        if ($values['invoiceAddress']['email']) {
            $job->setAtsMode(new AtsMode(AtsMode::MODE_EMAIL, $values['invoiceAddress']['email']));
        } else {
            $job->setAtsMode(new AtsMode(AtsMode::MODE_NONE));
        }

        $locations = $job->getLocations();

        foreach ($values['locations'] as $locStr) {
            $loc = new Location($locStr);
            $locations->add($loc);
        }

        $this->jobRepository->store($job);

        return $job;
    }

    private function sendMails($job, $order, $values)
    {

        $this->mailer->send($this->mailer->get(
            'Gastro24/SingleJobMail',
            [
                'template' => 'gastro24/mail/single-job-pending',
                'email'    => $values['invoiceAddress']['email'],
                'name'     => $values['invoiceAddress']['name'],
                'subject'  => 'Ihre Anzeige wartet auf Freischaltung.',
                'vars'     => [
                    'job' => $job,
                    'invoice' => $order->getInvoiceAddress(),
                ],
            ]
        ));

        $this->mailer->send($this->mailer->get(
            'Gastro24/SingleJobMail',
            [
                'template' => 'gastro24/mail/single-job-created',
                'admin'    => true,
                'subject'  => 'Eine Einzelanzeige wurde erstellt.',
                'vars'     => [
                    'job' => $job,
                    'order' => $order,
                ],
            ]
        ));

    }

    private function createOrder(\Jobs\Entity\Job $job, $values)
    {
        $invoiceAddress = new InvoiceAddress();
        foreach ($values['invoiceAddress'] as $setter => $value) {
            $invoiceAddress->{"set$setter"}($value);
        }

        $snapshotBuilder = new Builder();
        $snapshot = $snapshotBuilder->build($job);
        $snapshot->setOrganizationName($values['invoiceAddress']['company']);
        $products = new ArrayCollection();

        $product = new Product();
        $product->setName('Einzelinserat')
                ->setProductNumber(1)
                ->setQuantity(1);

        $products->add($product);


        $data = [
            'type' => OrderInterface::TYPE_JOB,
            'taxRate' => $this->orderOptions->getTaxRate(),
            'price' => 0,
            'invoiceAddress' => $invoiceAddress,
            'currency' => $this->orderOptions->getCurrency(),
            'currencySymbol' => $this->orderOptions->getCurrencySymbol(),
            'entity' => $snapshot,
            'products' => $products,
        ];

        $order = $this->orderRepository->create($data);
        $this->orderRepository->store($order);

        return $order;
    }
}
