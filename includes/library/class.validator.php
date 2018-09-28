<?php

class Entry
{
	private static $form = array(
		'clientOptIn' => array('label' => '[CUSTOM LABEL REQUIRED]', 'type' => 'checkbox', 'required' => false, 'noValue' => 0),
		'stationOptIn' => array('label' => '[CUSTOM LABEL REQUIRED]', 'type' => 'checkbox', 'required' => false, 'noValue' => 0, 'default' => 'checked'),
		'termsOptIn' => array('label' => '[CUSTOM LABEL REQUIRED]', 'type' => 'checkbox', 'required' => true),

		// 'photo' => array('label' => 'Upload your photo', 'type' => 'file', 'required' => true, 'specialCheck' => 'file'),

		'gender' => array('label' => 'Title:', 'type' => 'dropDown', 'required' => false, 'noValue' => 'U', 'options' => array('M-Mr' => 'Mr', 'F-Mrs' => 'Mrs', 'F-Miss' => 'Miss', 'F-Ms' => 'Ms'), 'default' => 'M-Mr'),

		'firstName' => array('label' => 'First name:', 'type' => 'text', 'required' => true),
		'surname' => array('label' => 'Surname:', 'type' => 'text', 'required' => true),

		'dateOfBirth' => array('label' => 'Date of birth:', 'type' => 'date', 'required' => true, /* 'minAge'=>18*/),

		'email' => array('label' => 'E-mail address:', 'type' => 'email', 'required' => true),
		'confirmEmail' => array('label' => 'Confirm e-mail address:', 'type' => 'email'),

		'phone' => array('label' => 'Contact number:', 'type' => 'tel', 'required' => true, 'specialCheck' => 'numbersOnly'),
		'postCode' => array('label' => 'Postcode:', 'type' => 'text', 'required' => true,)
	);

	// TYPES: text, email, tel, checkbox, gender, dob

	private static $e = array(
		'answer' => 'Please, <strong>answer the question</strong>.',
		'tooLong' => 'Your answer is too long, please shorten the answer.',

		'gender' => 'Please select your <strong>title</strong>.',
		'firstName' => 'Please enter your <strong>first name</strong>.',
		'surname' => 'Please enter your <strong>last name</strong>.',

		'dateOfBirth' => 'Please, enter your <strong>date of birth</strong>',
		'formatDate' => 'Please make sure a date <strong>is valid</strong>',
		'tooYoung' => 'Sorry, but you have to be <strong>18 years old</strong> to enter the competition',

		'email' => 'If you\'d like to receive news and information, please enter your <strong>e-mail address</strong>.',
		'formatEmail' => 'Please enter your e-mail address in the format <strong>name@domain.com</strong>.',
		'dodgyEmail' => 'Sorry, the form couldn\'t be submitted. Please try again later.<br />If you continue to see this error, please get in touch with us at <a href="mailto:info@thisisglobal.com">info@thisisglobal.com</a>.',

		'password' => 'Please enter a password.',

		'phone' => 'Please enter your daytime contact <strong>phone number</strong>.',
		'formatPhone' => 'Please use <strong>digits (0 - 9) only</strong>.',

		'postCode' => 'Please enter your <strong>post code</strong>; we won\'t use this to send you mail, pinkie swear, we just want to know what region you\'re from to know you better.',

		'termsOptIn' => 'You must <strong>read and agree to</strong> the terms and conditions before entering the competition.',
		'confirmAge' => 'Sorry, you must be over <strong>18 years</strong>.',

		'problemGeneral' => 'Sorry, there was a problem submitting the form. Please try again later.',

		'tooFast' => 'Sorry, there was a problem submitting the form. Please wait <strong><span id="timeout">60</span> seconds</strong> before trying again.',
		'tooSlow' => 'Sorry, for security, this competition has a <strong>30 minute expiry time</strong>. Please try again.',
		'noCSRF' => 'Sorry, there was a problem submitting the form. Please refresh your browser and try again, or contact <a href="mailto:info@thisisglobal.com">info@thisisglobal.com</a> if you keep seeing this message.',

		'enterCaptcha' => 'Please <strong>enter the characters</strong> you see in the image below.',
		'captchaDoesntMatch' => 'Sorry, that didn\'t match. Try again, though!',

		'file' => 'Please <strong>choose a file to upload</strong>.',
		'fileFormat' => 'Sorry, <strong>we can\'t upload that kind of file</strong>. Please choose another file.',
		'fileSize' => 'Sorry, there is a <strong>2 MB size limit</strong> on files; please pick a smaller file.',
	);
	private static $bannedNS = array('iphh.de');
	private static $bannedEmails = array('2rainmail.org.uk', 'awesomemail.us', 'barchor.org.uk', 'cannotmail.org.uk', 'course-manager.co.uk', 'crymet.org.uk', 'cupcaker.us', 'drecom01.co.uk', 'emailbaker.us', 'freggnet.co.uk', 'hoodmail.co.uk', 'indigoable.net', 'kreahnet.org.uk', 'laurelbaker.net', 'lonynet.oeg.uk', 'mailbreaker.co.uk', 'mobiledatamail.com', 'moussenetmail.co.uk', 'movenextweb.com', 'mywheelbox.org.uk', 'mywheelboxmail.org.uk', 'navyngrey.com', 'pluntermail.org.uk', 'prainnet.org.uk', 'rackernet.org.uk', 'railosnet.co.uk', 'rottmail.co.uk', 'runracemail.org.uk', 'runwaynet.org.uk', 'sherrymail.co.uk', 'shortsmail.co.uk', 'stickique.com', 'stonetimenet.co.uk', 'squeezer.us', 'tangerineinternet.com', 'telph1line.org.uk', 'thehamblins.org.uk', 'threemailnet.co.uk', 'tigerweb.org.uk', 'tyermail.org.uk', 'vickywilson.co.uk', 'wonandron.co.uk', 'wormail.co.uk', 'absoluteweb.info', 'customer-care-research.com', 'customercontactservices.com', 'customer-dynamics.com', 'customerjourney.net', 'darklin.info', 'investorsgroup.com', 'investorsinproperty.com', 'investorsplanning.co.uk', 'leadsdirect.biz', 'leadsdirect.co.uk', 'nutmail.info', 'purpleweb.info', 'satinmaker.info', 'wipenet.info');


	public function __construct()
	{
		$this->details = array();
		$this->errors = array();
	}

	private function printInput($name)
	{
		switch (self::$form[$name]['type']) {
			case 'text':
			case 'email':
			case 'tel':
				return '<input type="text" id="' . $name . '" name="' . $name . '" value="' . (isset($this->details[$name]) && !empty($this->details[$name]) ? $this->details[$name] : '') . '"' . (isset(self::$form[$name]['required']) && self::$form[$name]['required'] === true ? ' required' : '') . ' />';
				break;
			case 'password':
				return '<input type="password" id="' . $name . '" name="' . $name . '" value="' . (isset($this->details[$name]) && !empty($this->details[$name]) ? $this->details[$name] : '') . '"' . (isset(self::$form[$name]['required']) && self::$form[$name]['required'] === true ? ' required' : '') . ' />';
				break;
			case 'checkbox':
				return '<input type="checkbox" id="' . $name . '" name="' . $name . '" value="1"' . ((isset($this->details[$name]) && $this->details[$name] == 1) || (!isset($this->details[$name]) && isset(self::$form[$name]['default']) && self::$form[$name]['default'] == 'checked') ? ' checked="checked"' : '') . '/>';
				break;
			case 'dropDown':
				$options = '';
				if (!isset(self::$form[$name]['options'])) {
					return 'WARNING: dropDown for ' . $name . ' doesn\'t have any options';
				}
				foreach (self::$form[$name]['options'] as $k => $o) {
					$options .= '<option value="' . $k . '"' . ((isset($this->details[$name]) && $this->details[$name] == $k) || (isset(self::$form[$name]['default']) && self::$form[$name]['default'] == $k) ? ' selected="selected"' : '') . '>' . $o . '</option>';
				}
				return '<select name="' . $name . '"><option value="' . self::$form[$name]['noValue'] . '">Please select...</option>' . $options . '
                </select>';
				break;
			case 'date':
				return '
                    <input class="date" type="text" id="' . $name . '_day" name="' . $name . '_day" value="' . (isset($this->details[$name]) && !empty($this->details[$name]) ? date('d', strtotime($this->details[$name])) : '') . '" maxlength="2" placeholder="DD" />
                    <input class="date" type="text" id="' . $name . '_month" name="' . $name . '_month" value="' . (isset($this->details[$name]) && !empty($this->details[$name]) ? date('m', strtotime($this->details[$name])) : '') . '" maxlength="2" placeholder="MM" />
                    <input class="date" type="text" id="' . $name . '_year" name="' . $name . '_year" value="' . (isset($this->details[$name]) && !empty($this->details[$name]) ? date('Y', strtotime($this->details[$name])) : '') . '" maxlength="4" placeholder="YYYY" />';
				break;
			case 'file':
				return '<input type="file" id="' . $name . '" name="' . $name . '"' . (isset($this->fileNames[$name]) && !empty($this->fileNames[$name]) ? ' data-fileName="' . $this->fileNames[$name] . '"' : '') . ' /><input type="hidden" class="fileName" id="fileName-' . $name . '" name="fileName-' . $name . '" value="' . (isset($this->details[$name]) && !empty($this->details[$name]) ? $this->details[$name] : '') . '" />';
			default:
				return 'WARNING: No case for "' . self::$form[$name]['type'] . '" is set!';
		}
	}

	public function printMultipleFields($fields, $wrap, $classes = '', $label = '')
	{
		foreach ($fields as $f) {
			if (!isset(self::$form[$f])) return '<' . $wrap . '>WARNING: field "' . $f . '" is not set!</' . $wrap . '>';
		}

		switch ($wrap) {
			case 'dd':
			case 'dt':

				$markup = '';

				/* PRINT OUT ERRORS */
				foreach ($fields as $f) {
					$markup .= (isset($this->errors[$f]) ? '<dt class="error">' . self::$e[$this->errors[$f]] . '</dt>' : '');
				}

				if ($label == '') {
					// NESTED DL
					$markup .= '<dd class="clearfix">';
					$classes = ($classes == '' ? '' : ' ' . $classes);
					foreach ($fields as $f) {
						$markup .= '
                                <dl class="nested ' . $f . $classes . '">
                                    <dt><label for="' . $f . '">' . self::$form[$f]['label'] . '</label>' . ((isset(self::$form[$f]['required']) && self::$form[$f]['required'] === true) ? ' *' : '') . '</dt>
                                    <dd>' . $this->printInput($f) . '</dd>
                                </dl>';
					}
					$markup .= '</dd>';
				} else {
					// ONE LABEL FOR ALL FIELDS
					$required = false;
					$classes = ($classes == '' ? '' : ' class="' . $classes . '"');
					foreach ($fields as $f) {
						$required = $required || (isset(self::$form[$f]['required']) && self::$form[$f]['required'] === true);
					}
					$markup .= '<dt' . $classes . '><label for="' . $fields[0] . '">' . $label . '</label>' . ($required === true ? ' *' : '') . '</dt>
                           <dd' . $classes . '>';
					foreach ($fields as $f) {
						$markup .= $this->printInput($f);
					}
					$markup .= '</dd>';
				}

				return $markup;
				break;

			default:
				return '<p>WARNING: template markup is missing for ' . $wrap . '!</p>';
		}

	}

	public function printField($field, $wrap, $classes = '', $label = '')
	{
		if (!isset(self::$form[$field])) return '<' . $wrap . '>WARNING: field "' . $field . '" is not set!</' . $wrap . '>';

		$field_string = '';
		$field_container_id = 'field-container-' . $field;
		$field_label = $label == '' ? self::$form[$field]['label'] : $label;
		$field_required = isset(self::$form[$field]['required']) && self::$form[$field]['required'] === true ? ' *' : '';
		$field_class = isset($classes) && !empty($classes) ? ' ' . $classes  : '';
		$field_errors = isset($this->errors[$field]) ? "<$wrap class='error'>" . self::$e[$this->errors[$field]] . "</$wrap>" : '';

		switch ($wrap) {
			case 'dd':
			case 'dt':
				$field_string .= "<dt class='field-container{$field_class}' id='$field_container_id'><label for='$field'>$field_label $field_required</label></dt>
                        <dd $field_class>" . $this->printInput($field) . "</dd>";
				break;
			case 'div':
				$field_string .= "<div class='field-container{$field_class}' id='$field_container_id'><label for='$field'>$field_label $field_required</label>" . $this->printInput($field) . "</div>";
				break;
			case 'li':
				$field_string .= "<li class='field-container{$field_class}' id='$field_container_id'>" . $this->printInput($field) . "<label for='$field'>$field_label $field_required</label></li>";
				break;
			case 'p':
				$field_string .= "<p class='field-container{$field_class}' id='$field_container_id'>" . $this->printInput($field) . "<label for='$field'>$field_label $field_required</label></p>";
				break;
			default:
				return '<p>WARNING: template markup is missing for ' . $wrap . '!</p>';
		}
		return $field_errors . $field_string;
	}

	public function answerElement($idx, $val, $el_type = 'li')
	{
		$checked = $this->getValue('answer') == $val ? ' checked="checked"' : '';
		$el_id = 'a' . $idx;
		$output = <<<OUTPUT
			<$el_type ontouchstart="this.classList.toggle('hover');">
			<div class="front">
			</div>
			<div class="back">
				<input type="radio" id="$el_id" name="answer"  value="$val" $checked />
				<label for="$el_id">$val</label>
            </div>
			</$el_type>
OUTPUT;
		return $output;
	}

	private function isRequired($field)
	{
		if (isset(self::$form[$field]['required']) && self::$form[$field]['required'] === true) {
			return true;
		} else if (isset(self::$form[$field]['required']) && self::$form[$field]['required'] !== false) {
			// FIGURE OUT THE STRANGE LOGIC OF REQUIRED FIELD
			if (!is_array(self::$form[$field]['required'])) {
				echo 'WARNING: Check "required" description in field "' . $field . '"!';
				return false;
			}

			foreach (self::$form[$field]['required'] as $conditions) {
				if (!is_array($conditions)) {
					echo 'WARNING: Check "required" description in field "' . $field . '"!';
					return false;
				}
				foreach ($conditions as $c => $v) {

					if (!isset($this->details[$c])) {
						echo 'WARNING: Value required to know if "' . $field . '" is required is not set yet. Check "' . $c . '" first before checking "' . $field . '"!';
						return false;
					}

					if ($this->details[$c] == $v) {
						return true;
					}
				}
			}
		}
	}

	public function checkValue($field)
	{
		// SET AN ERROR
		if (isset(self::$form[$field]['required']) && self::$form[$field]['required'] === true) {
			$this->errors[$field] = $field;
		} else if (isset(self::$form[$field]['required']) && self::$form[$field]['required'] !== false) {
			// FIGURE OUT THE STRANGE LOGIC OF REQUIRED FIELD
			if (!is_array(self::$form[$field]['required'])) {
				echo 'WARNING: Check "required" description in field "' . $field . '"!';
				return;
			}

			foreach (self::$form[$field]['required'] as $conditions) {
				if (!is_array($conditions)) {
					echo 'WARNING: Check "required" description in field "' . $field . '"!';
					return;
				}
				foreach ($conditions as $c => $v) {

					if (!isset($this->details[$c])) {
						echo 'WARNING: Value required to know if "' . $field . '" is required is not set yet. Check "' . $c . '" first before checking "' . $field . '"!';
						return;
					}

					if ($this->details[$c] == $v) {
						$this->errors[$field] = $field;
						goto checkValue;
					}
				}
			}
		}

		checkValue:

		switch (isset(self::$form[$field]['specialCheck']) ? self::$form[$field]['specialCheck'] : self::$form[$field]['type']) {

			case 'whiteListEmails':
				if (isset($_POST[$field]) && !empty($_POST[$field])) {
					$this->details[$field] = safeChecks($_POST[$field]);

					if ($this->isValidEmail($this->details[$field]) == false) {
						$this->errors[$field] = 'formatEmail';
					} else {
						unset($this->errors[$field]);

						// Test if the email is on the dodgy list
						$domain = explode('@', strtolower($this->details[$field]));
						if (in_array($domain[1], self::$bannedEmails)) {
							$this->errors = array();
							$this->errors['general'] = 'dodgyEmail';
						}

						// Test if it's on dodgy servers
						$data = dns_get_record($domain[1], DNS_NS);

						// If it fails, so there's no risk of losing a real entry
						if ($data === false) {
						}
						foreach (self::$bannedNS as $i) {
							foreach ($data as $v) {
								if (strpos($v['target'], $i) !== false) {
									$this->errors = array();
									$this->errors['general'] = 'dodgyEmail';
								}
							}
						}
					}
				}
				break;
			case 'numbersOnly':
				if (isset($_POST[$field]) && !empty($_POST[$field])) {
					$this->details[$field] = safeChecks($_POST[$field]);
					unset($this->errors[$field]);
					if ($this->hasNumbers($this->details['phone']) == 0) {
						$this->errors['phone'] = 'formatPhone';
					}
				}
				break;
			/* case 'dropDown':
				 if (isset($_POST[$field]) && !empty($_POST[$field])) {
					 $this->details[$field] = safeChecks($_POST[$field]);
					 unset( $this->errors[$field] );
					 echo $field.': '.$this->details[$field].'</br>';
				 } else { $this->details[$field] = ( isset(self::$form[$field]['noValue']) ? self::$form[$field]['noValue'] : ''); }
				 break;*/
			case 'date':
				if (isset($_POST[$field . '_day']) && !empty($_POST[$field . '_day']) && isset($_POST[$field . '_month']) && !empty($_POST[$field . '_month']) && isset($_POST[$field . '_year']) && !empty($_POST[$field . '_year'])) {
					$day = safeChecks($_POST[$field . '_day']);
					$month = safeChecks($_POST[$field . '_month']);
					$year = safeChecks($_POST[$field . '_year']);

					if (@checkdate($month, $day, $year)) {

						$this->details[$field] = $year . '-' . $month . '-' . $day;

						if (isset(self::$form[$field]['minAge'])) {

							$latestBday = strtotime('-' . self::$form[$field]['minAge'] . ' years +1 day');

							if (strtotime($this->details[$field]) > $latestBday) {
								$this->setError($field, 'tooYoung');
							} else {
								unset($this->errors[$field]);
								break;
							}

						} else {
							unset($this->errors[$field]);
							break;
						}

					} else {
						$this->setError($field, 'formatDate');
					}
				} else {
					$this->details[$field] = (isset(self::$form[$field]['noValue']) ? self::$form[$field]['noValue'] : '0000-00-00');
				}

				break;
			case 'file':
				if (isset($_POST['prettyUpload-' . $field]) && !empty($_POST['prettyUpload-' . $field])) {
					unset($this->errors[$field]);
				} else {
					$this->errors[$field] = 'file';
				}
				break;
			default:
				if (isset($_POST[$field]) && !empty($_POST[$field])) {
					$this->details[$field] = safeChecks($_POST[$field]);
					unset($this->errors[$field]);
				} else {
					$this->details[$field] = (isset(self::$form[$field]['noValue']) ? self::$form[$field]['noValue'] : '');
				}
				break;
		}
	}

	public function checkAll()
	{
		foreach (self::$form as $field => $d) {
			$this->checkValue($field);
		}
	}

	private function isValidEmail($email)
	{
		return preg_match('/^[_a-z0-9-\']+(\.[_a-z0-9-\']+)*@[a-z0-9-\']+(\.[a-z0-9-\']+)*(\.[a-z]{2,4})$/i', $email);
	}

	private function hasNumbers($no)
	{
		return preg_match('/[0-9]/', $no);
	}

	public function resetForm($formID, $error, $database, $table)
	{

		$session = $_SESSION;
		session_destroy();
		session_start();

		// Write the details we can get to the error table, to help us see what's happening
		$details = 'Post data: ' . print_r($_POST, 1) . "\n\n" . 'Session data: ' . print_r($session, 1);
		if (isset($formID) && !empty($formID)) {
			$details .= 'Competition: ' . $formID;
		}

		if (!class_exists('myPDO')) {
			require('class.connect.php');
		}

		$db = new myPDO();

		$q = $db->prepare('INSERT INTO dataManager.rejectedCompetitionData(`database`, `table`, details, error, ipAddress, dateSubmitted) VALUES ("' . $database . '", "' . $table . '", :details, :error, "' . $_SERVER['REMOTE_ADDR'] . '", NOW())');
		$q->execute(array('details' => $details, 'error' => $error));

		$db = null;
	}

	public function recordEntry($formID, $table, $js)
	{
		if (!class_exists('myPDO')) {
			require('class.connect.php');
		}
		$db = new myPDO();

		// error_log(print_r($this->details,1),0);

		unset($this->details['termsOptIn']);
		unset($this->details['password']);
		$this->details['competition'] = $formID;
		$this->details['js'] = $js;

		// FORM LISTS
		$names = $tokens = '';
		foreach ($this->details as $f => $value) {
			$names .= $f . ', ';
			$tokens .= ':' . $f . ', ';
		}

		$q = $db->prepare('INSERT INTO ' . $table . '(' . $names . ' dateSubmitted, ipAddress) VALUES (' . $tokens . ' NOW(), "' . $_SERVER['REMOTE_ADDR'] . '")');
		$q->execute($this->details);

		if ($q->errorCode() != '00000') {
			echo '<!-- ';
			print_r($q->errorInfo());
			echo ' -->';
			return false;
		}
		return true;
	}

	public function setValue($name, $value)
	{
		$this->details[$name] = $value;
		unset($this->errors[$name]);
	}

	public function setError($name, $err)
	{
		$this->errors[$name] = $err;
	}

	public function getValue($name)
	{
		return (isset($this->details[$name]) ? $this->details[$name] : '');
	}

	public function printErrors()
	{
		$err = array();
		//print_r($this->errors);

		foreach ($this->errors as $k => $errorKey) {
			$err[$k] = self::$e[$errorKey];
		}
		echo json_encode($err);
		// echo '<!-- ';
		//  print_r($err);
		//  echo ' -->';
	}

	public function hasErrors()
	{
		return count($this->errors) > 0;
	}

	private function handleFile($field, $fileName, $location, $thumbSize)
	{

		$originalLocation = $location . (!empty($thumbSize) ? 'original/' : '');

		if (!file_exists($originalLocation)) {
			echo '<p>WARNING: location for original files not found. Please create a folder named ' . $originalLocation . '.</p>';
			return false;
		}

		$file = $_FILES[$field];

		// WE NEED TO SHOW TEMPORARY NAME IN CASE THERE WERE MISTAKES IN OTHER UPLOAD FIELDS
		$this->fileNames[$field] = safeChecks(basename($file['name']));

		if ($file['error'] > 0) {
			return false;
		}

		$f = explode('.', safeChecks(basename($file['name'])));
		$ext = strtolower($f[count($f) - 1]);

		$blacklist = array('text', 'audio', 'application', 'x-script', 'multipart', 'x-conference', 'x-form', 'message', 'x-world', 'chemical', 'x-model', 'video');
		$tempLocation = $file['tmp_name'];
		$mime = explode('/', mime_content_type($tempLocation));
		if (in_array($mime[0], $blacklist)) {
			$this->setError($field, 'fileFormat');
			return false;
		}

		checkSize:
		if ($file['size'] > 2097152) {
			$this->setError($field, 'fileSize');
			return false;
		}

		$patt = array('@"@', '@&@', '@;@');
		$rep = array('', '', '');

		$fileName = preg_replace($patt, $rep, stripslashes($fileName . '.' . $ext));
		if (!move_uploaded_file($tempLocation, $originalLocation . $fileName)) {
			$this->setError($field, 'problemGeneral');
			return false;
		}
		chmod($originalLocation . $fileName, 0644);

		if (!empty($thumbSize)) {
			$thumbsLocation = $location . 'thumbs/';
			if (!file_exists($thumbsLocation)) {
				echo '<p>WARNING: location for original files not found. Please create a folder named ' . $thumbsLocation . '.</p>';
				return false;
			}
			$this->makeThumbnail($originalLocation . $fileName, $thumbsLocation . $fileName, $thumbSize, $ext);
		}

		return $fileName;
	}

	private function makeThumbnail($src, $destination, $size, $ext)
	{
		if (!isset($size['width']) || !isset($size['height'])) {
			echo '<p>WARNING: thumbSize set incorrectly when calling a checkFile(). It must include width and height in pixels, for example: array(\'width\'=>\'100px\', \'height\'=>\'100px\'). Don\'t set thumbSize at all, if thumbs are not needed.</p>';
			return false;
		}

		switch ($ext) {
			case 'png':
				$originalImg = imagecreatefrompng($src);
				break;

			case 'gif':
				$originalImg = imagecreatefromgif($src);
				break;

			default:
				$originalImg = imagecreatefromjpeg($src);
		}

		$originalWidth = imagesx($originalImg);
		$originalHeight = imagesy($originalImg);
		$ratio = $originalWidth / $originalHeight;
		$targetWidth = $size['width'];
		$targetHeight = $size['height'];

		if ($ratio < 1) {
			$srcX = 0;
			$srcY = 0;
			$srcWidth = $originalWidth;
			$srcHeight = $targetHeight * ($originalWidth / $targetWidth);

			// Resize again if the source area's dimensions are not tall enough.
			if ($originalHeight < $srcHeight) {
				$resizeLevel = $originalHeight / $srcHeight;
				$srcWidth = ($originalWidth * $resizeLevel);
				$srcHeight = $originalHeight;
			}
		} else {
			$srcY = 0;
			$srcX = ($originalWidth / 2) - ($originalHeight / 2);
			$srcHeight = $originalHeight;
			$srcWidth = $targetWidth * ($originalHeight / $targetHeight);
		}
		$img = imagecreatetruecolor($targetWidth, $targetHeight);
		imagecopyresampled($img, $originalImg, 0, 0, $srcX, $srcY, $targetWidth, $targetHeight, $srcWidth, $srcHeight);

		switch ($ext) {
			case 'png':
				imagepng($img, $destination);
				break;

			case 'gif':
				imagegif($img, $destination);
				break;

			default:
				imagejpeg($img, $destination, 90);
		}
	}

	public function checkFile($field, $fileName, $location, $thumbSize = array())
	{

		if ($this->isRequired($field)) {
			$this->errors[$field] = 'file';
		}
		if (!empty($_FILES[$field]['tmp_name'])) {
			echo $_POST['fileName-' . $field];
			// IF WE HAD A CORRECT FILE UPLOADED ALREADY, THAT ONLY MEANS IT WAS CHANGED
			// AND WE SHOULD DELETE THE PREVIOUS ONE
			if (isset($_POST['fileName-' . $field]) && !empty($_POST['fileName-' . $field])) {
				if (file_exists($location . safeChecks($_POST['fileName-' . $field]))) {
					unlink($location . safeChecks($_POST['fileName-' . $field]));
				}
			}

			$fileName = $this->handleFile($field, $fileName, $location, $thumbSize);
			if ($fileName) {
				$this->setValue($field, $fileName);
			}
		} else if (isset($_POST['fileName-' . $field]) && !empty($_POST['fileName-' . $field])) {

			if (isset($_POST['prettyUpload-' . $field]) && !empty($_POST['prettyUpload-' . $field])) {
				$this->fileNames[$field] = safeChecks($_POST['prettyUpload-' . $field]);
			}
			// THIS APPLIES WHEN THERE ARE MORE THAN ONE UPLOAD FIELD
			// NOT TO LOOSE VALUE OF THE FIRST ONE IF THERE WAS AN ERROR WITH THE SECOND,
			// THERE WILL BE $_POST['fileName-'.$field] set.
			$this->setvalue($field, safeChecks($_POST['fileName-' . $field]));
		}
	}
}