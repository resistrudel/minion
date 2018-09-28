<?php
session_start();
date_default_timezone_set('Europe/London');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Brand and other tag data, constant across all campaigns.
include(dirname(__FILE__) . '../../../_config/data-shared.include.php');

include('common.data.php');
include('common.functions.php');

// Determine the brand, based on the domain.
$domain = $_SERVER['SERVER_NAME'];
if (array_key_exists($domain, $brand_domains)) {
    $brand = $brand_domains[$domain];

    // Check that that brand has been enabled for this campaign in the $siteDetails variable.
    if (!in_array($brand, $siteDetails['brands'])) {
        die('Invalid Brand.');
    }
} else {
    die('Invalid URL: check that the current domain is defined in _config/data-shared');
}

// Include the content file *after* the $brand variable has been defined, so that it can be used in the content.
include('common.content.php');

// The $timestamp variable is used for selecting time-specific content. It defaults to the current time unless
// 	overwritten by the 'preview' variable in the url.
$timestamp = time();
if (isset($_GET['preview']) && !empty($_GET['preview'])) {
    $preview = safeChecks($_GET['preview']);
    $timestamp = strtotime($preview);
}

// Use the findContent function to see if there's a content file for the particular timestamp. If there is, load the
// 	content file.
$lastChange = findContent($timestamp);
if ($lastChange !== false) {
    $content = getContent($lastChange);
}

// GET THE PAGE
// IF IT'S A ONE-PAGER, $page = 'home' IS ENOUGH.
// if (isset($_GET['p']) && !empty($_GET['p'])) {$page = safeChecks($_GET['p']);}
// if (!isset($page) || !array_key_exists($page, $content)) { $page = 'home'; }
$page = 'home';

// IF IT'S A ONE-PAGER FOR MULTIPLE BRANDS, THEN PAGE MAY BE REPLACED WITH BRAND:
//$page = $brand;
$text = $content[$page];

// Check if there is a class specified for the page, add it to the $class variable for the header to use.
$class = isset($text['class']) && !empty($text['class']) ? ' ' . $text['class'] : '';

// If it's a microsite for multiple brands, put the content one level deeper in the array.
//$text = $content[$brand][$page];

// If it's a site for multiple brands that has a lot of content that is constant across all brands, it's useful
//  to put that content in $content['all'] and access it in the page via the $commonText variable
// ONE-PAGER: $commonText = $content['all'];
// MICROSITE: $commonText = $content['all'][$page];

// Set the Derrick/Gigya urls based on whether the site is live or in testing stage.
$derrick_API_key = IS_LIVE ? $brands[$brand]['derrick']['live']['API_KEY'] : $brands[$brand]['derrick']['test']['API_KEY'];
$derrick_env = IS_LIVE ? 'live' : 'development';