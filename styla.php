<?php
/*
 * Copyright (C) 2019 Styla GmbH. All rights reserved.
 */

    class Styla {
        public static $seoPrefix = "https://seo.styla.com/clients/";
        public static $cdnPrefix = "https://client-scripts.styla.com/";
        public static $versionPrefix = "https://live.styla.com/api/version/";
        public static $prophetUrl = "https://engine.styla.com/init.js";

        public $clientName = null;
        public $contentKey = null;
        public $rootPath = null;

        private $seo = null;

        public function __construct(
            $clientName,
            $rootPath = "/"
        ) {
            $this->clientName = $clientName;
            $this->rootPath = $rootPath;

            $this->init();
        }

        public function getMagazineMetaTags() {
            $scriptSource = join([
                self::$cdnPrefix,
                "scripts/clients/",
                $this->clientName,
                ".js"
            ]);

            $styleSource = join([
                self::$cdnPrefix,
                "styles/clients/",
                $this->clientName,
                ".css"
            ]);

            return join([
                "<link rel=\"preconnect\" href=\"https://engine-nle.styla.com\">",
                "<link rel=\"preconnect\" href=\"https://config.styla.com\">",
                "<link rel=\"preconnect\" href=\"https://static-cdn.styla.com\">",
                "<link rel=\"preconnect\" href=\"https://styla-prod-us.imgix.net\">",
                "<script src=\"",
                $scriptSource,
                "\"></script>",
                "<link rel=\"stylesheet\" type=\"text/css\" href=\"",
                $styleSource,
                "\">"
            ]);
        }

        public function getLPMetaTags() {
            return join([
                "<link rel=\"preconnect\" href=\"https://engine.styla.com\">",
                "<script src=\"", self::$prophetUrl, "\"></script>"
            ]);
        }

        public function getMagazineTag() {
            return join([
                "<div id=\"stylaMagazine\"></div>"
            ]);
        }

        public function getLPTag($params = []) {
            return join([
                "<div",
                $params["client"] ? "data-styla-client=\"" . $params["client"] . "\"" : "",
                $params["slot"] ? "data-styla-slot=\"" . $params["slot"] . "\"" : "",
                $params["content"] ? "data-styla-content=\"" . $params["content"] . "\"" : "",
                "></div>"
            ]);
        }

        public function getSEOHead() {
            $this->getSEOContent();

            if ( $this->seo === null ) {
                return '';
            }

            if ( !isset( $this->seo->html->head ) ) {
                return '';
            }

            return stripslashes($this->seo->html->head);
        }

        public function getSEOBody() {
            $this->getSEOContent();

            if ( $this->seo === null ) {
                return '';
            }

            if ( !isset( $this->seo->html->body ) ) {
                return '';
            }

            return stripslashes($this->seo->html->body);
        }

        public function getSEOContent() {
            if ( $this->seo !== null ) {
                return $this->seo;
            }

            // build URL to fetch data from
            $url = join([
                self::$seoPrefix,
                $this->clientName,
                "?url=",
                $this->contentKey
            ]);

            return $this->fetchSEOContent($url);
        }

        private function fetchSEOContent($url) {
            // If not in cache yet -> fetch SEO information for current content
            $rawSeoResponse = file_get_contents($url);

            // Check the SEO API response
            if(!$rawSeoResponse) {
                return;
            }

            // Transform response to JSON
            $seoResponse = json_decode($rawSeoResponse);

            // Check if response is really an oci_fetch_object
            if(!is_object($seoResponse)) {
                return;
            }

            // Check if json has status code
            if(!isset($seoResponse->status)) {
                return;
            }

            // check if response code is 2XX
            if($seoResponse->status !== 200) {
                return;
            }

            $this->seo = $seoResponse;

            return $seoResponse;
        }

        // Extract 'offset' param from given URL object
        private function getOffsetParam() {
            $url = parse_url($_SERVER['REQUEST_URI']);
            $query = isset($url['query']) ? $url['query'] : "";

            if(strpos($query, 'offset=') === false) {
                return '';
            }

            return $query;
        }

        private function buildContentKey() {
            $url = parse_url($_SERVER['REQUEST_URI']);
            $currPath = $url['path'];

            $contentKey = isset($_REQUEST["origUrl"]) ? $_REQUEST["origUrl"] : "/";

            if($this->rootPath !== "/"){
                $path = join([ "/", $this->rootPath ]);

                // search for rootpath in $path and remove it
                $contentKey = str_replace($path, "", $currPath);
            }

            // get offset of current URL
            $offset = $this->getOffsetParam();
            $offsetKeySuffix = !empty($offset) ? "&" . $offset : "";

            $this->contentKey = join([ $contentKey, $offsetKeySuffix ]);
        }

        private function init() {
            $this->buildContentKey();
        }
    }
?>
