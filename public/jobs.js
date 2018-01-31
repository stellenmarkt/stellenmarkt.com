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
;(function ($) {

    function onPagiantorLoaded()
    {
        $('.featured-image-box').matchHeight({
            byRow: true,
            property: 'height',
            target: null,
            remove: false
        });
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
            .on('click.jobboard', '.internal-apply-link', onInternalApplyLinkClicked);
        onPagiantorLoaded();
    });

})(jQuery); 
 
