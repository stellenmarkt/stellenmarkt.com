<?php

// chdir in config file so tests environment can chdir to this sandbox
chdir(dirname(__DIR__));
return [
    'modules' => [
        'Core',
        'SlmQueue',
        'Auth',
        'Geo',
        'Organizations',
        'Jobs',
        'Solr',
        'Settings',
        'Applications',
        'Cv',
        'Orders',
        'JobsByMail',
        'Stellenmarkt',
        'Landingpages',
        'Sitemap',
        'ExportBA',
    ],
];
