<?php
require_once('common.constants.php');

$formID = safeChecks($_GET['formID']);

include('library/class.security.php');
$security = (isset($_SESSION[$campaign][$formID]['security']) ? unserialize($_SESSION[$campaign][$formID]['security']) : false);

if (!$security) {
	echo 'Nice try!';
	return;
}
echo $security->updateJsToken();

$_SESSION[$campaign][$formID]['security'] = serialize($security);