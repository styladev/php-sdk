<?php

/**
 * caching.php
 *
 * Provides basic caching functions and caching setup
 * Used in seo.php
 *
 * @author     Christian Korndoerfer
 * @copyright  2016 Styla GmbH
 * @version    1.0.0
 * @link       http://github.com/styladev/phpintegration
 * @since      File available since Release 1.0.0
 */

// Create caching folder
if (!file_exists('cache')) {
	mkdir('cache', 0777, true);
}

// helper function to escape they caching $key
function escapeKey($key){
    $key = $key == "/" ? "root" : $key;
    $key = preg_replace('/[^A-Za-z0-9]/', "", $key);
    return $key;
}

// function to read a key from the cache and check it's expiration; provides $SEO_head and $SEO_body for index.php
function readFromCache($key) {
    $key = escapeKey($key);

    $file = "cache/path_".$key.".php";

    return $file;
}

// function to write a new key to the cache
function writeToCache($key, $head, $body, $expire) {
    // Build file contents
    $fileContent = "<?php\n/* Diese Datei wurde von Styla generiert. */\n\n\n";
    $fileContent .= "\t\$expire = ".$expire.";\n";
    $fileContent .= "\t\$SEO_head = '".addslashes($head)."';\n";
    $fileContent .= "\t\$SEO_body = '".addslashes($body)."';\n";
    $fileContent .= "\n\n?>";

    // escape $key to create valid file name
    $key = escapeKey($key);

    // create new file handle
    $handle = fopen("cache/path_".$key.".php", "w");

    // write content to file
    fwrite($handle, $fileContent);
    fclose($handle);
}

?>
