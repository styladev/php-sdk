<?php
/**
 * config.php
 *
 * Basic configurations of the magazine to display
 * Can be changed according to needs
 *
 * @author     Christian Korndoerfer
 * @copyright  2016 Styla GmbH
 * @version    1.0.0
 * @link       https://github.com/styladev/php-sdk
 * @since      File available since Release 1.0.0
 */

$config = [
    "domain" => "boohoo-uk",     // your domain name, e.g. "kiveda"
    "rootpath" => "uk",     // your magazines rootpath,
                                    // e.g. for yourdomain.com/magazine the rootpath would be "magazine"

    // Don't modify below this line unless you know what you're doing
    // -------------------------------------------------------------------------

    "SEO_server" => "http://seo.styla.com/clients/",
    "CDN_server" => "http://cdn.styla.com/",
    "Version_server" => "http://live.styla.com/api/version/"
]
?>
