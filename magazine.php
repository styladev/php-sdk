<?php
    /**
     * magazine.php
     *
     * Fetches the right script and styles for the configured magazine
     * Used in index.php in the <head> section
     *
     * @author     Christian Korndoerfer
     * @copyright  2016 Styla GmbH
     * @version    1.0.1
     * @link       https://github.com/styladev/php-sdk
     * @since      File available since Release 1.0.0
     */


    $cachedVersion = "cache/version.php";

    // Check if there is already a cached version
    if(!is_file($cachedVersion)){
        $debug_version_cache = "false";

        // Fetch current version from Styla Version server
        $version = @file_get_contents($config["Version_server"].$config["domain"]);
        $version = $version ? $version : "";

        // cache the current $version
        writeToCache($version);
    }
    else{
        $debug_version_cache = "true";

        // Include cached content ($SEO_head and $SEO_body for index.php)
        include_once $cachedVersion;

        // Delete caching file if expired (older 600s)
        if (time() - filemtime($cachedVersion) >= 600) {
    		unlink($cachedVersion);
    	}
    }
?>

<script src="<?php echo $config["CDN_server"]."scripts/clients/".$config["domain"].".js?v=".$version ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $config["CDN_server"]."styles/clients/".$config["domain"].".css?v=".$version ?>">
