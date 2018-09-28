<?php
/*
 * IMPORTANT: The following constant must be set to true when the page/site is live. This is used to determine whether
 * to use the live or testing URLs for Derrick/Gigya.
 */
define('IS_LIVE', true);

// Campaign name - this must be the client name followed by the year.
$campaign = 'UniversalPictures2017';

// Set to true if the site uses multiple brands.
$multiple_brands = true;

// Set the Krux tracking category or categories - See ../_config/data-shared.include.php. If krux tracking isn't
//  needed, this variable can be deleted.
//$kruxCategories = array(
//    'travel'
//);

/**
 * $client array contains the following variables:
 *
 *    ['key']        Client key, which will be used for tracking in analytics.
 *    ['slogan']    The slogan will be the alt/fallback text for the client logo.
 *    ['link']    The url for the link on the client logo in the header.
 *    ['name']    Client name, used in the opt-in text.
 */
$client = array(
    'key' => 'despicableMe',
    'slogan' => 'Despicable Me',
    'link' => 'http://www.dm3tickets.co.uk ',
    'name' => 'Universal Pictures'
);

/**
 * $siteDetails array contains the following variables:
 *
 *    ['brands']     The brands for this campaign (must use a key value from the $brands array).
 *    ['baseURL']    The url this campaign will be served from. Change 'template' to your campaign directory name.
 *    ['localPath']  The system path for this campaign. Again, change 'template' to your campaign directory name.
 *    ['database']   The database for this campaign. This will probably be 'competitions' and the current year, i.e:
 *                      'competitions2016'.
 *    ['table']      The table name should be the brand followed by the competition name, i.e: 'heartSpecsavers2016'.
 *                      For campaigns with multiple brands using different tables, use an array with the brand
 *                      shortcode as the key for the table name, i.e: array('heart' => 'heartCompname2016')
 */
$siteDetails = array(
    'brands' => array('heart'),
    'baseURL' => 'http://' . $_SERVER['HTTP_HOST'] . '/despicable-me/',
    'localPath' => $_SERVER['DOCUMENT_ROOT'] . '/despicable-me/',
    'database' => 'competitions2017',
    'table' => array(
        'heart' => 'heartDespicableMe32017',
    )
);