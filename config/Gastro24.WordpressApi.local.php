<?php
/**
 * YAWIK-Gastro24
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
namespace Gastro24;

$idMap = [
    'hotelfachmann' => 2,
    'koch' => 7,
    'cuisinier' => 23,
    'barkeeper' => 43,
    'rezeption' => 45,
    'konditor' => 48,
];

return [
    'options' => [
        'Gastro24/WordpressApiOptions' => [
            'options' => [
                'baseUrl' => '',
                /*'httpClientOptions' => [
                    'auth' => ['', ''],
                ],*/
                'idMap' => $idMap
            ],
        ],
        WordpressApi\Options\WordpressContentSnippetOptions::class => [
            'options' => [
                'idMap' => $idMap
            ],
        ],
    ],
];
