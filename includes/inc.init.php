<?php
ini_set('display_errors', 1);
session_start();
error_reporting(E_ERROR | E_WARNING);
include_once('common.content.php');
include_once('common.functions.php');
$gamedata = $content['home']['quiz'];
if (isset($_SESSION[$campaign][$gamedata['id']]['submitted']) && $_SESSION[$campaign][$gamedata['id']]['submitted'] == 1) {
	$competition_entered = true;
}

$game_id = $gamedata['id'];
$questions = $gamedata['items'];
$question_count = count($questions);

// Set the session variables if they don't already exist.
if (!isset($_SESSION[$game_id]['score']) || isset($_POST['restart'])) {
	reset_session_variables($game_id);
}
