<?php
include("styla.php");

$styla = new Styla('showcase', [
    "content" => "modules"
]);

header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?=$styla->getSEOHead() ?>
    <?=$styla->getLPMetaTags() ?>

    <style>
        @font-face{font-family:NeueHaasUnicaPro;font-weight:700;src:url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprobold.eot);src:url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprobold.eot?#iefix) format("embedded-opentype"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprobold.woff2) format("woff2"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprobold.woff) format("woff"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprobold.ttf) format("truetype"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprobold.svg#wf) format("svg")}
        @font-face{font-family:NeueHaasUnicaPro;font-weight:400;src:url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprolight.eot);src:url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprolight.eot?#iefix) format("embedded-opentype"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprolight.woff2) format("woff2"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprolight.woff) format("woff"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprolight.ttf) format("truetype"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprolight.svg#wf) format("svg")}
        @font-face{font-family:NeueHaasUnicaPro;font-weight:200;src:url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprothin.eot);src:url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprothin.eot?#iefix) format("embedded-opentype"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprothin.woff2) format("woff2"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprothin.woff) format("woff"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprothin.ttf) format("truetype"),url(https://static-cdn.styla.com/styla/fonts/neuehaas/unicaprothin.svg#wf) format("svg")}

        body {
            font-family: NeueHaasUnicaPro, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            margin: 0;
            padding: 1.5em 0 0.5em 0;
            display: flex;
            justify-content: center;
            border-bottom: 1px solid;
            background-color: #f5f2ec;
            top: 0;
            width: 100%;
            position: fixed;
            z-index: 9000;
        }

        .header .logo {
            width: 150px;
            margin: 0 auto;
        }

        .header table td {
            padding-left: 10px;
        }

        .header .header-wrap {
            display: flex;
        }

        .header .header-wrap > div {
            padding: 0 1em;
        }

        .container {
            margin-top: 200px
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-wrap">
            <div>
                <div class="logo">
                    <svg class="c-masthead--logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 112.33 30.33"><path class="o-styla-logo o-hide" d="M39.85,20.42l1-.78A4.9,4.9,0,0,0,45,21.82c2.09,0,3.74-1.28,3.74-3.23s-1.94-2.72-4-3.37-4.38-1.49-4.38-4.13c0-2.24,2-3.88,4.63-3.88a5.57,5.57,0,0,1,4.59,2.1l-1,.73a4.18,4.18,0,0,0-3.63-1.76c-1.9,0-3.37,1.15-3.37,2.81,0,2,1.88,2.53,3.89,3.18C47.75,15,50,15.85,50,18.6,50,21,48,22.93,45,22.93a6.24,6.24,0,0,1-5.19-2.51"></path><polygon class="o-styla-logo o-hide" points="53.66 7.43 53.66 8.54 58.53 8.54 58.53 22.72 59.77 22.72 59.77 8.54 64.64 8.54 64.64 7.43 53.66 7.43"></polygon><polygon class="o-styla-logo o-hide" points="78.93 7.43 74.48 14.3 70.04 7.43 68.66 7.43 73.89 15.45 73.89 22.72 75.13 22.72 75.13 15.41 80.31 7.43 78.93 7.43"></polygon><polygon class="o-styla-logo o-hide" points="84.76 7.43 84.76 22.72 93.41 22.72 93.41 21.57 86 21.57 86 7.43 84.76 7.43"></polygon><path class="o-styla-logo o-hide" d="M104.34,9l3.22,8.25h-6.47Zm-.66-1.61-6,15.3H99l1.67-4.27H108l1.69,4.27H111l-6-15.3Z"></path><path class="o-styla-logo" d="M16,10.12H2.18a1.39,1.39,0,0,1,0-2.79H16a1.39,1.39,0,0,1,0,2.79"></path><path class="o-styla-logo" d="M21.59,16.47H5.74a1.39,1.39,0,1,1,0-2.79H21.59a1.39,1.39,0,1,1,0,2.79"></path><path class="o-styla-logo" d="M26.22,22.82H12.44a1.39,1.39,0,1,1,0-2.79H26.22a1.39,1.39,0,1,1,0,2.79"></path><path class="o-styla-logo" d="M20.09,29.17H4.24a1.39,1.39,0,1,1,0-2.79H20.09a1.39,1.39,0,0,1,0,2.79"></path><path class="o-styla-logo" d="M24.63,3.78H8.78A1.39,1.39,0,0,1,8.78,1H24.63a1.39,1.39,0,0,1,0,2.79"></path></svg>
                </div>
                <h2>PHP Integration example</h2>
            </div>
            <div>
                <table style="width: 100%;">
                    <tbody>
                    <tr>
                        <th>Client name</th>
                        <td><?=$styla->clientName ?></td>
                    </tr>
                    <tr>
                        <th>Rootpath</th>
                        <td><?=$styla->rootPath ?></td>
                    </tr>
                    <tr>
                        <th>Content Key</th>
                        <td><?=$styla->contentKey ?></td>
                    </tr>
                    <tr>
                        <th>CDN Server</th>
                        <td><?=$styla::$cdnPrefix ?></td>
                    </tr>
                    <tr>
                        <th>SEO Server</th>
                        <td><?=$styla::$seoPrefix ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- This is where the landing page will be displayed -->
        <?=$styla->getLPTagWithSeo() ?>
    </div>
</body>
</html>
