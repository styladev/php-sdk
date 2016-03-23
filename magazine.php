<?php
    /**
     * magazine.php
     *
     * Fetches the right script and styles for the configured magazine
     * Used in index.php in the <head> section
     *
     * @author     Christian Korndoerfer
     * @copyright  2016 Styla GmbH
     * @version    1.0.0
     * @link       http://github.com/styladev/phpintegration
     * @since      File available since Release 1.0.0
     */
    // Get current version of scripts and styles
    $version = @file_get_contents($config["Version_server"].$config["domain"]);
    $version = $version ? $version : "";
?>

<script src="<?php echo $config["CDN_server"]."scripts/clients/".$config["domain"].".js?v=".$version ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $config["CDN_server"]."styles/clients/".$config["domain"].".css?v=".$version ?>">
