
<?php

/**
 * seo.php
 *
 * Fetches SEO information from the cache or the Styla SEO API
 * Provides $SEO_head and $SEO_body used in the index.php
 *
 * @author     Christian Korndoerfer
 * @copyright  2016 Styla GmbH
 * @version    1.0.1
 * @link       https://github.com/styladev/php-sdk
 * @since      File available since Release 1.0.0
 */

// Extract SEO key from current URL
if($config['rootpath'] == "/"){
    $key = array_key_exists("origUrl", $_REQUEST) ? $_REQUEST["origUrl"] : "/";
}
else{
    // Extract path and query information from current URL
    $url = parse_url($_SERVER['REQUEST_URI']);
    $path = $url['path'];
    $query = isset($url['query']) ? $url['query'] : "";

    // search for rootpath in $path and remove it
    $key = str_replace("/".$config['rootpath'], "", $path);

    // Append ?offset parameter if set
    if (strpos($query, 'offset=') !== false) {
        $key .= '?'.$query;
    }
}

// escapes the key string
$escapedKey = escapeKey($key);

// function to fetch SEO information from the Styla SEO API
function fetchFromSeoApi($key, $url){
    // If not in cache yet -> fetch SEO information for current content
    $SEO_content = @file_get_contents($url);

    // Check the SEO API response
    if($SEO_content != FALSE){
        // Transform response to JSON
        $SEO_json = json_decode($SEO_content);

        // Check if response is really an oci_fetch_object
        if(is_object($SEO_json)){
            // Check if json has status code
        	if(isset($SEO_json->status)){
                // check if response code is 2XX
            	if(substr((string)$SEO_json->status, 0, 1) == '2'){
                	// extract caching duration and SEO information from JSON
                	$data["expire"] = isset($SEO_json->expire) ? $SEO_json->expire / 60 : 60; // in s
                    $data["head"] = isset($SEO_json->html->head) ? $SEO_json->html->head : "";
                    $data["body"] = isset($SEO_json->html->body) ? $SEO_json->html->body : "";
                    $data["key"] = $key;

                    // Save SEO head, body and expire data to cache
        			writeToCache($data);

                    // return JSON to use it in index.php
                    return $SEO_json;
            	} else {
                    // non existent pages/sites should return 404
                    header("HTTP/1.0 404 Not Found");
                }
            }
        }
    }
}

// Check if $key is already in the cache
if(!is_file('cache/path_'.$escapedKey.'.php')){
    $debug_cache = "false";

    // build URL to fetch data from
    $url = $config['SEO_server'].$config['domain']."?url=".$key;
    $SEO_json = fetchFromSeoApi($key, $url);

    // Set SEO <head> information for index.php
    $SEO_head = isset($SEO_json->html->head) ? $SEO_json->html->head : "";

    // set SEO <body> information for index.php
    $SEO_body = isset($SEO_json->html->body) ? $SEO_json->html->body : "";
}
else{
    $debug_cache = "true";

    // Read the file from the cache
    $cachedFile = readFromCache($escapedKey);

    // Include cached content ($SEO_head and $SEO_body for index.php)
    include_once $cachedFile;

    // Delete caching file if expired
    if (time() - filemtime($cachedFile) >= $expire) {
		unlink($cachedFile);
	}
}
?>
