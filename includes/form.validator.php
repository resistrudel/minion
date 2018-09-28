<?php

// If this is being requested via ajax, include the constants. If it's being pulled in as an include, we can already
//   access the values in that file.
if (isset($_POST['js'])) {
	$js = true;
	require_once('common.constants.php');
}

$formID = (isset($_POST['formID']) && !empty($_POST['formID']) ? safeChecks($_POST['formID']) : $text['competition']['name']);

// Get the brand from the POST variables and check it exists, otherwise default to the $brand variable defined in the session.
$formBrand = isset($_POST['brandID']) && array_key_exists($_POST['brandID'], $brands) ? $_POST['brandID'] : $brand;

// Set the database table to use, based on whether the competition is using multiple brands or not.
$table = $multiple_brands ? $siteDetails['table'][$formBrand] : $siteDetails['table'];

include('library/class.security.php');
include('library/class.validator.php');

$security = (isset($_SESSION[$campaign][$formID]['security']) ? unserialize($_SESSION[$campaign][$formID]['security']) : new Security($formBrand, $campaign));
// IN CASE YOU HAVE SAVED USER DETAILS IN THE SESSION:
// $form = ( isset($_SESSION[$campaign][$formID]['details']) ? unserialize($_SESSION[$campaign][$formID]['details']) : new Entry());
$form = new Entry();

// Form validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$formID = safeChecks($_POST['formID']);
	if (isset($_SESSION[$campaign][$formID]['skipTheChecks']) && $_SESSION[$campaign][$formID]['skipTheChecks'] == '1') {
		goto startCheck;
	}

	$err = $security->checkTokens();
	if ($err !== false) {
		switch ($err) {
			case 'enterCaptcha':
			case 'captchaDoesntMatch':
				$form->setError('captcha', $err);
				break;
			case 'tooFast':
				$form->setError('general', $err);
				$form->resetForm($formID, $security->getErrorMsg(), $siteDetails['database'], $table);
				goto end;
			default:
				$form->setError('general', $err);
				$form->resetForm($formID, $security->getErrorMsg(), $siteDetails['database'], $table);
				goto end;
		}
	}
	startCheck:
	// We're safe! On with the rest

	$form->setError('answer', 'answer');
	// THERE MIGHT BE SOME SPECIAL LOGIC TO ANSWER FIELD.
	// LIKE SELECT FORM DROP DOWN AND IF "else" IS SELECTED, THEN CHECK INPUT FIELD
	// OR TAKE FROM TEXTAREA, WHICH MEANS LENGTH MUST BE CHECKED TOO
	if (isset($_POST['answer']) && !empty($_POST['answer'])) {
		$form->setValue('answer', safeChecks($_POST['answer']));

		// Answer length comparison: we're comparing the $_POST version of 'answer' because there are no
		//  special characters added via safeChecks or other functions.
		if (isset($text['competition']['answerLimit']) && strlen($_POST['answer']) > $text['competition']['answerLimit']) {
			$form->setError('answer', 'tooLong');
		}
	}

	$form->checkAll();

	if (isset($text['competition']['fileUpload']) && $text['competition']['fileUpload']) {
		if (isset($js) && !$form->hasErrors()) {
			$_SESSION[$campaign][$formID]['skipTheChecks'] = '1';
			echo json_encode(array('fileupload' => true));
			goto exitValidation;
		} else if (!isset($js)) {
			// ADD ALL FILE FIELDS
			$fileName = strtolower($form->getValue('firstName')) . '_' . strtolower($form->getValue('surname')) . '_' . date('zHisu');

			// Uncomment this out if there is an image file to be processed
			// $thumbsize = array('width'=>224, 'height'=>373);
			// $form->checkFile('photo', $fileName, $siteDetails['localPath'] . 'entries/', $thumbsize);
		}
	}

	end:

	if ($form->hasErrors()) {
		if (isset($js)) {
			$form->printErrors();
		}
	} else {
		if ($form->recordEntry($formID, $table, (isset($js) ? 1 : 0))) {
			$_SESSION[$campaign][$formID]['submitted'] = 1;
			if (isset($js)) {
				echo json_encode(array('submitted' => true));
			}
		};
	}
}

exitValidation:

// Create a random CSRF token
$security->updateTimeToken();
$security->updateCaptcha();
if (isset($error) && count($error) > 0 && !isset($error['general'])) {
	$security->updateTimeToken('-10 seconds');
}

// SAVE IN SESSION
$_SESSION[$campaign][$formID]['security'] = serialize($security);
// IN CASE YOU WANT TO SAVE DETAILS IN SESSION
// $_SESSION[$campaign][$formID]['details'] = serialize($form);

// End of file: form.validator.php