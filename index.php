<?php
    /**
     * index.php
     *
     * Displays the Styla magazine including SEO information
     *
     * @author     Christian Korndoerfer
     * @copyright  2016 Styla GmbH
     * @version    1.0.1
     * @link       https://github.com/styladev/php-sdk
     * @since      File available since Release 1.0.0
     */

    require_once('config.php');     // general configuration
    require_once('caching.php');    // provides caching functions
    require_once('seo.php');        // fetches SEO content and utilizes caching

    header('Content-Type: text/html; charset=UTF-8'); // Force setting the charset to UTF-8
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- SEO content generated in seo.php -->
    <?php echo stripslashes($SEO_head) ?>

    <!-- Styla Magazine script and styles generated in magazine.php -->
    <?php require('magazine.php') ?>

    <!-- Just here for the demo styling, not necessary -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" media="screen" title="bootstrap" charset="utf-8">
</head>
<body>
    <div class="jumbotron">
        <div class="container">
            <h1>PHP Integration example</h1>
            <h2>Current config:</h2>
            <p>
                <strong>Magazine domain:</strong> <?php echo $config["domain"] ?> <br>
                <strong>Rootpath:</strong> <?php echo $config["rootpath"] ?> <br>
                <strong>Script Server:</strong> <?php echo $config["CDN_server"] ?> <br>
                <strong>SEO server:</strong> <?php echo $config["SEO_server"] ?> <br>
                <strong>Current SEO API request:</strong> <?php echo $config["SEO_server"].$config['domain']."?url=".$key ?> <br>
                <strong>Reading SEO from cache:</strong> <?php echo $debug_cache ?> <br>
                <strong>Reading Version from cache:</strong> <?php echo $debug_version_cache." (".$version.")" ?>
            </p>
        </div>
    </div>
    <div class="container">
        <!-- This is where the magazine will be displayed -->
        <div id="stylaMagazine"></div>
        <?php echo stripslashes($SEO_body) ?>
    </div>
</body>
</html>
