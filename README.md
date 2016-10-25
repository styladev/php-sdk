
# INTRODUCTION

This is an example implementation for fetching SEO content for a Styla magazine using PHP. It’s based on the [Styla Plugin Documentation](http://static.styla.com/docs/pluginDocumentation.pdf) and fetches SEO content from the Styla SEO API according to the current site/content (root magazine, story, category, etc.), injects it into the page and caches it for future use.

## GET IT RUNNING

#### Option A

Clone this repository or download and extract it as [a zip file](https://github.com/styladev/php-sdk/archive/master.zip). Open a terminal window and change directory to the cloned directory containing the `index.php` file. Run `php -S localhost:8000` to ramp up a local PHP server. You should now be able to navigate to http://localhost:8000/ in your browser.

#### Option B

Another way to get a server like this running you can utilize common software like e.g. [MAMP](https://www.mamp.info/en/downloads/) which will serve as a reference for this guide. Once MAMP is installed and running, place all extracted files in the “htdocs” directory. Alternatively you can change the path to the extracted `index.php` within the MAMP settings.

---

As an example for this README we are going to use "Kiveda" as an client and use MAMP for our local server:

* If your magazine is using a specific rootpath (e.g. /magazin is the rootpath for Kiveda) create new folder within htdocs named after that rootpath and place all files except the .htaccess in there:

![folder structure within htdocs and rootpath=magazin](http://i.imgur.com/4lgoOVe.png)

* Open the `config.php` file and change `{domainname}` to your magazine domain name and `{rootpath}` to your magazines rootpath. If you’re not sure about your domain name and or rootpath, please contact support@styla.com. Your `config.php` should now look something like this (“kiveda” is used as a domain name in this example):

![config.php](http://i.imgur.com/3sGagaa.png)

* Open the `.htaccess` file and modify the {rootpath} in line 8 to your rootpath. If your rootpath happens to be "/" you can just delete the {rootpath}.

After doing so, open your browser and access the MAMP server (default is localhost:9000/<yourrootpath>). You should see the following site, with your magazine in it:

![The magazine](http://i.imgur.com/uaxlWr0.png)

The grey area mainly serves as some kind of debug area and displays information about the current config and state of the site. Don’t worry, this is only part of the Demo Integration and not part of your magazine, which can be seen below the debug area.

When inspecting the page you should already see SEO content being pulled in:

![SEO content](http://i.imgur.com/b1EHWBu.png)

## HOW IT WORKS

All requests made to the local server will be redirected back to the index.php via RewriteRules in the `.htaccess` file. The application will thereby fetch anything after the rootPath (e.g. /story/some_random_story_title) and use it as a $key which will be send to the SEO API along with your domain name. Here is a quick example of the flow:

When calling e.g. “http://phpint.dev:1337/magazin/story/grifflos-gluecklich_984059/” with the domain name “kiveda” set in the `config.php` the “/story/grifflos-gluecklich_984059/” part of the URL will serve as the $key for the SEO API, since the rootPath of kiveda is “magazin”. The resulting SEO API call will look like this:

[http://seo.styla.com/clients/kiveda?url=/story/grifflos-gluecklich_984059/ ](http://seo.styla.com/clients/kiveda?url=/story/grifflos-gluecklich_984059/ )

The response of the SEO API call contains a JSON with all the SEO information for that specific story. This example app will make use of the “html -> head”, “html -> body” and the “expire” values. “html -> head” will become $SEO_head (see `<head>` section in the `index.php`), “html -> body” will become `$SEO_body` (see `<body>` section in the `index.php`) and “expire” will serve as an expiration indicator regarding caching, since both `$SEO_head` and `$SEO_body` will be cached.

---

The core file `index.php` defines the basic DOM structure and pulls in all required files:

1. `config.php` : Basic configuration of the magazine.
    - “domain” – your domain name (should be modified)
    - “SEO_server” – server address that will provide SEO information
    - “CDN_server” – server address that will provide the core magazine script and styles
    - “Version_server” – server that provides the latest version of the script and style files

    ![Version](http://i.imgur.com/ytKeQaJ.png)

2. `caching.php` : contains methods to read (`readFromCache()`) and write (`writeToCache()`)from and to the cache. The example app uses a local file cache and will create a “cache” directory in the root directory of the app containing php files which will contain the cached SEO information. Those methods will be used in the `seo.php` file.

    - _Note: Other caching methods (e.g. database caching or memcached) are possible, but aren’t part of this example._

3. `seo.php` : Provides a method to fetch a `$key` from a specified URL (in this case the “SEO_server” config) and checks if the current key already exists in the cache. If so, the SEO information for the current `$key` will be fetched from the cache. The cached file will contain the `$SEO_head` and `$SEO_body` variables used in the `index.php`. If there is no such $key in the cache or the cache has expired the SEO information will be fetched from the SEO API using the provided `fetchFromSeoApi` method.

4. `magazine.php` : This file is required in the `<head>` section of the `index.php` and injects the script and styles of the configured “domain”. It also attaches the correct version of those files fetched from the “Version_server” and caches the version number. As soon as the script file is executed the magazine will render inside the `<div id=”stylaMagazine”></div>` DOM element found in the index.php file.
