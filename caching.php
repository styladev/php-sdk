<?php

/**
 * caching.php
 *
 * Provides basic caching functions and caching setup
 * Used in seo.php
 *
 * @author     Christian Korndoerfer
 * @copyright  2016 Styla GmbH
 * @version    1.0.1
 * @link       https://github.com/styladev/php-sdk
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
function writeToCache($data) {
	// Build file contents
	$fileContent = "<?php\n\t/* Diese Datei wurde von Styla generiert. */\n\n\n";

	// if $data is an array, the array should contain SEO data
	if(is_array($data)){
	    $fileContent .= isset($data["expire"]) ? "\t\$expire = ".$data["expire"].";\n" : "\t\$expire = '';\n";
	    $fileContent .= isset($data["head"]) ? "\t\$SEO_head = '".addslashes($data["head"])."';\n" : "\t\$SEO_head = '';\n";
	    $fileContent .= isset($data["body"]) ? "\t\$SEO_body = '".addslashes($data["body"])."';\n" : "\t\$SEO_body = '';\n";
	    $fileContent .= "\n\n?>";

	    // escape $key to create valid file name
	    $key = isset($data["key"]) ? escapeKey($data["key"]) : "";

	    // create new file handle
	    $handle = fopen("cache/path_".$key.".php", "w");
	}
	else{	// else it's just the current version string
		$fileContent .= "\t\$version = '".$data."';\n";
		$fileContent .= "\n\n?>";

		// create new file handle
	    $handle = fopen("cache/version.php", "w");
	}
    // write content to file
    fwrite($handle, $fileContent);
    fclose($handle);
}

?>
