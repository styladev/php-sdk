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
        private $contentId = null;
        private $slotId = null;

        private $useCurl = false;
        private $forceRootPath = false;

        public function __construct(
            $clientName,
            $params = []
        ) {
            $this->clientName = $clientName;
            $this->contentId = $params["content"] ? $params["content"] : null;
            $this->slotId = $params["slot"] ? $params[""] : null;

            $this->rootPath = $params["rootPath"] ? $params["rootPath"] : null;

            $this->useCurl = in_array('curl', get_loaded_extensions());
            $this->forceRootPath = $params["forceRootPath"] ? $params["forceRootPath"] : false;

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
                "<script src=\"", self::$prophetUrl, "\" async></script>"
            ]);
        }

        public function getMagazineTag() {
            return join([
                "<div id=\"stylaMagazine\"></div>"
            ]);
        }

        public function getLPTag($params = []) {
            $client = $this->clientName;
            $content = $this->contentId;
            $slot = $this->slotId;

            if ( $params["client"] ) {
                $client = $params["client"];
            }

            if ( $params["content"] ) {
                $content = $params["content"];
            }

            if ( $params["slot"] ) {
                $slot = $params["slot"];
            }

            return join(' ', [
                "<div",
                $client ? "data-styla-client=\"" . $client . "\"" : "",
                $content ? "data-styla-content=\"" . $content . "\"" : "",
                $slot ? "data-styla-slot=\"" . $slot . "\"" : "",
                $params["noClosedTag"] ? ">" : "></div>"
            ]);
        }

        public function getLPTagWithSeo($params = []) {
            $params["noClosedTag"] = true;

            return join([
                $this->getLPTag($params),
                $this->getSEOBody(),
                "</div>"
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

            return $this->seo->html->head;
        }

        public function getSEOBody() {
            $this->getSEOContent();

            if ( $this->seo === null ) {
                return '';
            }

            if ( !isset( $this->seo->html->body ) ) {
                return '';
            }

            return $this->seo->html->body;
        }

        public function getSEOContent() {
            if ( $this->seo !== null ) {
                return $this->seo;
            }

            $resolvedContentKey = $this->contentKey;

            $isLP = $this->contentId !== null;

            if ( $isLP ) {
                $resolvedContentKey = $this->contentId;
            }

            if ( $this->forceRootPath && isset($this->rootPath) ) {
                $resolvedContentKey = join([
                    $this->contentKey,
                    $resolvedContentKey
                ]);
            }

            // build URL to fetch data from
            $url = join([
                self::$seoPrefix,
                $this->clientName,
                "?",
                $isLP ? "type=area&" : "",
                "url=",
                $resolvedContentKey
            ]);

            $this->seo = $this->fetchSEOContent($url);

            return $this->seo;
        }

        private function fetchSEOContent($url) {
            // If not in cache yet -> fetch SEO information for current content
            $rawSeoResponse = $this->fetchResource($url);

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

            return $seoResponse;
        }

        private function fetchResource($url) {
            if (!$this->useCurl) {
                return file_get_contents($url);
            }

            $req = curl_init();

            curl_setopt($req, CURLOPT_HEADER, false);
            curl_setopt($req, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($req, CURLOPT_URL, $url);
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);

            $res = curl_exec($req);

            curl_close($req);

            return $res;
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
            if ( $this->forceRootPath && isset($this->rootPath) ) {
                $this->contentKey = $this->rootPath;
                return;
            }

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
