
	<div id="form-container" class="form-container">

		<input type="hidden" id="formID" name="formID" value="<?= $formID ?>"/>
		<input type="hidden" id="brandID" name="brandID" value="<?= $brand ?>"/>
		<input type="hidden" name="<?= $security->getPhpLabel() ?>" value="<?= $security->getPhpToken(); ?>"/>


        <div class="social-connect-container">
            <div class="wrapper">
                <div class="text-box">
                    <h2>Login to enter the competition</h2>
                </div>
            </div>
			<div id="derrick-social-auth-component">
            </div>
		</div>
		<div id="user-details" class="user-details details column">
			<div class="wrapper">
                <h3 class="title">Please complete the following details to enter the competition:</h3>
				<fieldset id="competitionDetails" class="competition-details">
					<div class="register-container">
						<?php
						echo $form->printField('gender', 'div', 'clearfix');
						echo $form->printField('firstName', 'div', 'clearfix');
						echo $form->printField('surname', 'div', 'clearfix');
						echo $form->printField('email', 'div', 'clearfix');
						echo $form->printField('dateOfBirth', 'div', 'clearfix');
						echo $form->printField('postCode', 'div', 'clearfix');
						echo $form->printField('phone', 'div', 'clearfix');
						?>

						<noscript>
							<?php if (!isset($userCaptcha)) {
								echoCaptcha();
							} ?>
						</noscript>
						<?php
						if (isset($userCaptcha)) {
							echoCaptcha();
						}
						// Un-comment the following line to print out the captcha code as plain text.
						// echo $security->spillTheBeans();
						?>
					</div>
				</fieldset>
				<fieldset id="competitionOptIns" class="competition-optins column">
					<?php
					echo $form->printField('clientOptIn', 'p', '', 'Please tick here if you wish to receive email updates from <a href="' . $client['link'] . '">' . $client['name'] . '</a>.');?>
                    <p>PLEASE SEE <a href="http://www.nbcuniversal.com/privacy">UNIVERSAL'S PRIVACY POLICY</a> FOR MORE INFORMATION ON WHAT HAPPENS TO YOUR DATA</p>
					<?php
                    echo $form->printField('stationOptIn', 'p', '', 'Yes! I want to be the first to hear about news, special promotions and offers from <a href="' . $brands[$brand]['brandURL'] . '" target="_blank">' . $brands[$brand]['name'] . '</a>.');
					echo $form->printField('termsOptIn', 'p', '', 'I have read and agreed to the <a href="' . $text['competition']['terms'] . '" target="_blank">Terms and Conditions</a> and <a href="' . $brands[$brand]['privacy'] . '" target="_blank">Privacy Policy</a>.');
					?>
					<p class="cta clear">
						<button type="submit" name="enter">Submit<span></span></button>
					</p>
					<small><span class="req">*</span> required field</small>
				</fieldset>
			</div>
		</div>

	</div>
<?php // End of file: form.competition.php