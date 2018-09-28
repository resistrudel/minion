<!DOCTYPE html>
<!--[if IE 6]>
<html lang="en" class="ie ie6 lte-ie-7 lte-ie-8 lte-ie-9"><![endif]-->
<!--[if IE 7]>
<html lang="en" class="ie ie7 lte-ie-7 lte-ie-8 lte-ie-9"><![endif]-->
<!--[if IE 8]>
<html lang="en" class="ie ie8 lte-ie-8 lte-ie-9"><![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie ie9 lte-ie-9"><![endif]-->
<!--[if !IE]>
<html lang="en"><!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <meta name="keywords" content="<?= $text['metaKeywords']; ?>"/>
    <meta name="description" content="<?= $text['metaDescription']; ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="author" content="Global Radio"/>

    <title><?= $text['metaTitle']; ?></title>
    <base href="<?= $siteDetails['baseURL']; ?>"/>

    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'
          data-noprefix>
    <script src="https://use.typekit.net/oni4zxg.js"></script>
    <script>try{Typekit.load({ async: true });}catch(e){}</script>
    <link href="/_shared/styles/derrick.social.theme.<?= $brand ?>.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/layout.css" media="screen"/>

    <link rel="shortcut icon" href="/_shared/favicons/<?= $brand ?>/favicon.ico" type="image/x-icon"/>
    <link rel="apple-touch-icon" href="/_shared/favicons/<?= $brand ?>/apple-touch-icon.png"/>
    <link rel="apple-touch-icon" sizes="57x57"
          href="/_shared/favicons/<?= $brand ?>/apple-touch-icon-57x57.png"/>
    <link rel="apple-touch-icon" sizes="72x72"
          href="/_shared/favicons/<?= $brand ?>/apple-touch-icon-72x72.png"/>
    <link rel="apple-touch-icon" sizes="76x76"
          href="/_shared/favicons/<?= $brand ?>/apple-touch-icon-76x76.png"/>
    <link rel="apple-touch-icon" sizes="114x114"
          href="/_shared/favicons/<?= $brand ?>/apple-touch-icon-114x114.png"/>
    <link rel="apple-touch-icon" sizes="120x120"
          href="/_shared/favicons/<?= $brand ?>/apple-touch-icon-120x120.png"/>
    <link rel="apple-touch-icon" sizes="144x144"
          href="/_shared/favicons/<?= $brand ?>/apple-touch-icon-144x144.png"/>
    <link rel="apple-touch-icon" sizes="152x152"
          href="/_shared/favicons/<?= $brand ?>/apple-touch-icon-152x152.png"/>

    <meta property="og:url" content="<?= $siteDetails['baseURL'] ?>"/>
    <meta property="og:title" content="<?= $text['metaTitle'] ?>"/>
    <meta property="og:image"
          content="/_shared/favicons/<?= $brand ?>/og-logo.jpg"/>
    <meta property="og:description" content="<?= $text['metaDescription'] ?>"/>
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="@thisisglobal"/>
    <meta name="twitter:title" content="<?= $text['metaTitle'] ?>"/>
    <meta name="twitter:description" content="<?= $text['metaDescription'] ?>"/>
    <meta name="twitter:image"
          content="/_shared/favicons/<?= $brand ?>/og-logo.jpg"/>

    <!--[if LTE IE 8]>
    <script src="/_shared/scripts/html5shiv-min.js" type="text/javascript"></script>
    <![endif]-->
</head>

<body class="no-js <?= $brand ?> <?= $page ?> <?= $class ?>">

<div id="frame">

    <!--[if LTE IE 7]><p style="background:orange;color:#000;padding:10px;font-size:1.2em;">We are sorry, but this page
        is not compatible with your browser anymore. Please, update you browser.</p><![endif]-->
