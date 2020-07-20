<?php
require 'vendor/autoload.php';        // Make sure to point this correctly

use WebPConvert\WebPConvert;

$source = $_GET['source'];            // Absolute file path to source file. Comes from the .htaccess
$destination = $source . '.webp';     // Store the converted images besides the original images (other options are available!)

WebPConvert::serveConverted($source, $destination, [
    'fail' => 'original',     // If failure, serve the original image (source). Other options include 'throw', '404' and 'report'
    //'show-report' => true,  // Generates a report instead of serving an image

    'serve-image' => [
        'headers' => [
            'cache-control' => true,
            'vary-accept' => true,
            // other headers can be toggled...
        ],
        'cache-control-header' => 'max-age=2',
    ],

    'convert' => [
        // all convert option can be entered here (ie "quality")
    ],
]);