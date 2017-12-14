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

    function onPaginatorLoaded()
    {
        var $form = $('#jobs-list-filter');
        $('#facets-apply').click(function() {
            $('.facet-checkbox').each(function() {
                var $checkbox = $(this),
                    name = $checkbox.attr('name');
                $form.find('input[name="' + name + '"]').remove();
                if ($checkbox.prop('checked')) {
                    $form.append('<input type="hidden" class="facet-param" name="' + name + '">');
                }
            });
            var facets={ r: [], c: [], i: [], t: [] };

            $form.find('.facet-param').each(function(){
                var $checkbox = $(this);
                var name = $checkbox.attr('name');
                var value=name.replace(/^[^\[]+\[(.*)\]$/, '$1');
                if (name.match(/^region/)) {
                    facets.r.push(value);
                } else if (name.match(/^organiz/)) {
                    facets.c.push(value);
                } else if (name.match(/^industry/)) {
                    facets.i.push(value);
                } else if (name.match(/^employ/)) {
                    facets.t.push(value);
                }
            }).remove();

            for (var key in facets) {
                if (facets[key].length) {
                    $form.append('<input type="hidden" name="' + key + '" value="' + facets[key].join('_') + '">');
                }
            }

            $form.trigger('submit');
        });

        $('#facets-reset').click(function() {
            $form.find('.facet-param').remove().end().submit();
        });

    }

    $(function() {
        onPaginatorLoaded();
        $('#jobs-list-container').on('yk-paginator-container:loaded.jobs-facets', onPaginatorLoaded);
    });

})(jQuery); 
 
