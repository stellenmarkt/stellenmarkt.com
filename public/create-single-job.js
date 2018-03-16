/**
 * YAWIK
 *
 * License: MIT
 * (c) 2013 - 2018 CROSS Solution <http://cross-solution.de>
 */

/**
 *
 * Author: Mathias Gelhausen <gelhausen@cross-solution.de>
 */
;(function ($) {

    var $form, $uriBox, $uriInput, $pdfBox, $pdfInput;

    function toggleInputs()
    {
        if ($uriBox.prop('checked')) {
            $uriInput.slideDown();
            $pdfInput.slideUp();
            $pdfInput.find('input').val('');
        } else {
            $uriInput.slideUp();
            $pdfInput.slideDown();
        }
    }

    $(function() {
        $form = $('#createsinglejob');
        $uriBox = $form.find('#csj-mode-uri-span input');
        $pdfBox = $form.find('#csj-mode-pdf-span input');
        $uriInput = $form.find('.csj-uri-wrapper');
        $pdfInput = $form.find('.csj-pdf-wrapper');
        toggleInputs();
        $('#csj-mode-uri-span, #csj-mode-pdf-span').click(toggleInputs);
    })
})(jQuery); 
 
