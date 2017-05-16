<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */

/*
 * Format:
 * [
 *      'name' => <name>, [required]
 *      'value' => <value>, [optional]
 *      'children' => [ // optional
 *          <name> // strings will be treated as ['name' => <name>]
 *          [
 *              'name' => <name>, [required]
 *              'value' => <value>, [optional]
 *              'children' => [ ... ]
 *       ]
 * ]
 */

return [
    'name' => 'Professions',
    'children' => [
        'Bar',
        'Service & Restaurant',
        'Bäckerei / Konditorei / Patisserie',
        'Catering',
        'Küche & Kantine',
        'Fleischwaren & Metzgerei',
        'Wellness / Beauty / Spa',
        'Hoteldirektion & Hotelmanagement',
        'Concierge & Room Service',
        'Housekeeping & Stewarding',
        'Hauswirtschaft & Wäscherei',
        'Rezeption & Hotelempfang',
        'Marketing & Sales',
        'Hotel & Tourismus',
        'Reiseführer & Tour Guide',
        'Besucher- & Gästebetreuung',
        'Sommelier',
        'Einkauf / F&B / Warenwirtschaft',
        'Kreuzfahrt',
        'Animation & Ferien',
        'Fitness & Sport',
        'Studenten & Aushilfen',
        'Systemgastronomie'
];

