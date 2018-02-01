/**
 * YAWIK
 *
 * License: MIT
 * (c) 2013 - 2017 CROSS Solution <http://cross-solution.de>
 */

/**
 *
 * Author: Mathias Gelhausen <gelhausen@cross-solution.de>
 */
;(function ($, window) {

    function onPagiantorLoaded()
    {
        $('.featured-image-box').matchHeight({
            byRow: true,
            property: 'height',
            target: null,
            remove: false
        });
    }

    function onApplyLinkClicked(event)
    {
        var $link = $(event.currentTarget);
        var isExternal = $link.hasClass('external-apply-link');
        var message = (isExternal ? '' : '<p>Bitte nutzen Sie die Bewerbungsmöglichkeit im Inserat, um sich zu bewerben.</p>')
                    + '<p><strong>Bitte beziehen Sie sich bei Ihrer Bewerbung auf <em>gastro24</em></strong></p>';
        var uri = $link.attr('href');

        BootstrapDialog.show({
            title: $('<h5 class="modal-title">Jetzt bewerben</h5>'),
            type: BootstrapDialog.TYPE_DEFAULT,
            message: $(message),
            closable: true,
            buttons: [
                {
                    label: 'Weiter',
                    action: function() { window.location.href = uri; },
                    cssClass: 'btn-primary'
                }
            ]
        });

        return false;
    }

    function onInternalApplyLinkClicked(event)
    {
        var $link = $(event.currentTarget);
        var $modal = $('#job-apply-modal');

        $modal.find('.modal-body').html('<iframe style="border: 0 solid black;" src="' + $link.attr('href') + '" width="100%" height="100%"></iframe>');
        $modal.modal('show');

        return false;
    }

    $(function() {
        $('#jobs-list-container').on('yk-paginator-container:loaded.jobboard', onPagiantorLoaded)
            .on('click.jobboard', '.internal-apply-link', onInternalApplyLinkClicked)
            .on('click.jobboard', '.external-apply-link, .no-apply-link', onApplyLinkClicked);


        onPagiantorLoaded();
    });

})(jQuery, window); 
 
