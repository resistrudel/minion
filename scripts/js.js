// @codekit-prepend "components/modernizr.js";
// @codekit-prepend "components/common.functions.js";

// Javascript is working, so remove the 'no-js' class from any elements that have it.
$('.no-js').removeClass('no-js').addClass('js');

// Assign the scroll function to 'a' elements of class 'scroll'.
$('body').find('a.scroll').click(function (event) {
    event.preventDefault();
    scrollToLink(this);
});

$(document).ready(function() {
    //find the first item/ question and display it
    var $currentItem = $('.items-container .item').first();
    $currentItem.addClass('active');

    //set the answered questions and the correct answers to 0 and define the total number of questions
    var questionCount = 0;
    var correctAnswers = 0;
    var totalNumberOfQuestions = quizAnswers.length;

    //click on any answer button
    $('.item button').click(function () {
        //increase the number of answered questions by one
        questionCount++;
        //find out which button is clicked
        var $clickedButton = $(this);
        //get the index of the current question/answer item (1, 2, 3) and make it into a string
        var currentItemIndex = $currentItem.attr('data-index')*1;
        //get all buttons/answers in the current question/answer item
        var $allButtonsInCurrentItem = $currentItem.find('button');
        //get the index of the button that is being clicked
        var currentButtonIndex = $allButtonsInCurrentItem.index($clickedButton);

        //disable all the other buttons if one is clicked so only one answer can be selected
        $allButtonsInCurrentItem.each(function() {
            if($(this) != $clickedButton) {
                $(this).attr('disabled', 'disabled');
            }
        });
        //if the selected answer is identical with the correct answer add class correct and increase the number of correctly answered questions by one
        if(currentButtonIndex === quizAnswers[currentItemIndex]) {
            $clickedButton.addClass('correct');
            correctAnswers++;
        } else {
            $clickedButton.addClass('incorrect');
        }

        //after 1.5s hide the current question and show either the next question (if it's question 1 or 2) or the results page (if it's question 3)
        setTimeout(function(){

            $currentItem.slideUp();
            if (questionCount < totalNumberOfQuestions) {
                $nextItem = $('.item[data-index=' + (currentItemIndex + 1) + ']');
            } else {
                $('.results #score').text(correctAnswers);
                $('.results #total').text(totalNumberOfQuestions);
                $nextItem = $('.results');
                $('.quiz-intro').slideUp();
            };

            $nextItem.slideDown();
            //reset current item to next item
            $currentItem = $nextItem;
        }, 1500);


        //when you click play again remove the results, make the first item the current item and remove all the classes from the buttons
        //set the answered questions and the correct answers to 0 again
        $('#restart').click(function() {
            $currentItem = $('.items-container .item').first();
            $('.results').slideUp();
            $currentItem.slideDown();
            $('.item button').removeClass('correct incorrect').removeAttr('disabled');
            questionCount = 0;
            correctAnswers = 0;
        });

        //when you click enter hide the question and show the entry form
        $('#enter').click(function() {
            $('.quiz .wrapper .quiz-container').slideUp();
            $('.question').fadeIn();
            $('.form-wrapper').fadeIn();
        });
    });



    $('.answers-container ul li').on('click', function () {
        console.log($(this));
            var $clickedAnswer = $(this);
            $clickedAnswer.addClass('clicked');
            $clickedAnswer.siblings().removeClass('clicked');
            $('html, body').animate({
                scrollTop: $(".form-wrapper").offset().top
            }, 2000);
    });
});



(function () {
    /**
     * CompetitionForm is used for controlling the competition form.
     *
     * @param {jQuery} $form The form that we're working with.
     *
     * @param {jQuery} $wrapper (Optional) The form wrapper, used for maintaining the layout when form is submitted.
     *
     * @param {jQuery} $container (Optional) The form container, the contents will be replaced with thank you message.
     *
     * @constructor
     */
    function CompetitionForm($form, $wrapper, $container) {
        this.$form = $form;
        this.$form_wrapper = $wrapper || $('body').find('#form-wrapper');
        this.$form_container = $container || this.$form_wrapper.find('#form-container');
        this.$userDetails = this.$form_container.find('#user-details');
        this.$questionSection = this.$form_container.find('#question');
        this.$inputs = this.$form.find('input');
        this.errors = []; // Errors array, will be populated by the checkForm function
        this.formID = this.$form.find('#formID').val();
        this.$answerLengthFields = this.$form.find('.answer-length') || false;
        this.derrickFailed = false;
        this._attachEvents();
        this.$form.prepend('<input type="hidden" id="js" name="js" value="1" />');
        this._addJS();
    }

    /**
     * Bind the various submission and validation events to the appropriate elements.
     *
     * @private
     */
    CompetitionForm.prototype._attachEvents = function () {
        this.$form.on('submit', this._onSubmit.bind(this));
        this.$userDetails.on('change', 'input[type=checkbox], input[type=radio]', this._checkValRemoveError.bind(this));
        this.$questionSection.on('change', 'input[type=checkbox], input[type=radio]', this._checkValRemoveError.bind(this));
        this.$questionSection.on('keyup', 'input, textarea', this._checkValRemoveError.bind(this));
        this.$userDetails.on('keyup', 'input, textarea', this._checkValRemoveError.bind(this));
        if (this.$answerLengthFields) {
            var _this = this;
            this.$answerLengthFields.each(function () {
                _this._initAnswerLength(this);
            });
        }
    };

    /**
     * Check that an element has a value assigned to it and clear the error message if it does.
     *
     * @param event
     *
     * @private
     */
    CompetitionForm.prototype._checkValRemoveError = function (event) {
        var $field = this.$form.find('#' + event.target.id);
        if ($field.val().length > 0) {
            this._removeError($field);
        }
    };

    /**
     * Submit the serialised form data and process the response.
     *
     * The data will only be submitted if:
     *  - the derrick object is present and the user is logged in; OR
     *  - derrick has failed altogether; in which case we need to just get on with capturing the data.
     *
     * @param event
     *
     * @private
     */
    CompetitionForm.prototype._onSubmit = function (event) {
        // Check that the user has either logged in or registered, or
        if (derrickPresentAndLoggedIn() || this.derrickFailed) {
            event.preventDefault();
            var _this = this;
            $.ajax({
                type: 'POST',
                url: 'includes/form.validator.php',
                data: this.$form.serialize(),
                cache: false,
                success: function (data) {
                    if (data.length) {
                        _this._checkForm(data);
                    }
                }
            });
        }
    };

    /**
     * Handles the data response from the ajax-submitted form and changes page layout as required.
     *
     * @param data
     *
     * @private
     */
    CompetitionForm.prototype._checkForm = function (data) {
        data = JSON.parse(data);

        // If the submission was successful, show the thank you response.
        if (data.submitted === true) {
            this._showThankYou();
            return;
        }

        // If the response indicates a file needs to be handled, re-submit the form without AJAX.
        else if (data.fileupload === true) {
            this.$form.find('input#js').remove();
            this.$form.submit();
            return;
        }

        // Reset the tokens we use for session checking.
        this._addJS();
        this._resetPHP();

        this.errors = data; // Assign the error data to the object's errors array.
        this._showErrors(); // Display the errors.
    };

    /**
     * Updates the form layout to hide the form and adds the 'success' content.
     *
     * @private
     */
    CompetitionForm.prototype._showThankYou = function () {
        var preview = getQueryVariable('preview'); // Check if this is a preview scenario.
        var _this = this;

        // Set the form wrapper height in pixels so that the layout doesn't jump when we update its content.
        this.$form_wrapper.width(this.$form_wrapper.outerWidth()).height(this.$form_wrapper.outerHeight());

        this.$form_wrapper.append('<div id="result" class="result" />'); // Add the result div to the form.
        var $result = this.$form_wrapper.find('#result');
        $result.hide(); // Hide the result div until it's populated and ready to show.

        // Populate the result div with the response from the success template.
        $result.load('includes/templ.success.php?formID=' + this.formID + (preview ? '&preview=' + preview : ''), function () {
            _this.$form_container.fadeOut(1200, function () { // Hide the existing form.
                $(this).remove(); // Then remove it.
                _this.$form_wrapper.addClass('thank-you');
                $result.fadeIn(1200); // Reveal the result div.
                $('html, body').animate({scrollTop: _this.$form_wrapper.offset().top - 70}, 900);
            });
        });
    };

    /**
     * Adds a hidden input with the updated js token value, for use in CSRF prevention.
     *
     * @private
     */
    CompetitionForm.prototype._addJS = function () {
        var i = $('input[name=' + j_ + ']'); // The original hidden token input field in the form.

        // Choose an element at random from the inputs in the form. This element will have the token field added before
        //   it. This randomizing of the token field's location increases security against automated attacks.
        var c = this.$inputs.length - 1;
        var r = Math.floor(Math.random() * c);
        var $randEl = this.$form.find('input:eq(' + r + ')');

        if (i.length) { // If the original token exists, remove it so we can replace it with a new one.
            i.remove();
        }

        // Call the server and reset the form's js token value for this session, then update the token field.
        $.ajax({
            type: 'GET',
            url: 'includes/inc.js.php',
            cache: false,
            data: {'formID': this.formID},
            success: function (data) {
                if (data.length) {
                    $('<input type="hidden" name="' + j_ + '" value="' + data + '" />').insertBefore($randEl);
                }
            }
        });
    };

    /**
     * Updates the hidden php token field, for use in CSRF prevention.
     *
     * @private
     */
    CompetitionForm.prototype._resetPHP = function () {
        $.ajax({
            type: 'GET',
            url: 'includes/inc.reset.php',
            cache: false,
            data: {'formID': this.formID},
            success: function (data) {
                if (data.length) {
                    $('input[name=' + t_ + ']').val(data);
                }
            }
        });
    };

    /**
     * Takes the profile and populates the user form with the data from it, using the _populateAndReplaceField
     * method, which hides the input field if it has a valid value.
     *
     * @param profile
     */
    CompetitionForm.prototype.populateForm = function (profile) {
        // Explode the returned DOB string to create elements for the dateOfBirth inputs.
        var dob_array = profile.dob.split("-");
        var dob_day = dob_array[2];
        var dob_month = dob_array[1];
        var dob_year = dob_array[0];

        var date_valid = dob_day > 0 && dob_month > 0 && dob_year > 1900;

        // Set the gender value to one that the form uses.
        var gender = profile.gender === 'M' ? 'M-Mr' : 'F-Ms';

        this._populateAndReplaceField('firstName', profile.first_name);
        this._populateAndReplaceField('surname', profile.last_name);
        this._populateAndReplaceField('email', profile.email);
        this._populateAndReplaceField('postCode', profile.postcode);
        this._populateAndReplaceField('phone', profile.phone);
        this._populateAndReplaceField('dateOfBirth_day', dob_day);
        this._populateAndReplaceField('dateOfBirth_month', dob_month);
        this._populateAndReplaceField('dateOfBirth_year', dob_year);
        this._populateAndReplaceField('gender', gender, 'select');
        if (profile.global_comms) {
            this.$form.find('input[name="stationOptIn"]').prop('checked', true);
            this.$form.find('input[name="stationOptIn"], label[for="stationOptIn"]').hide();
        }
        if (date_valid) {
            this.$userDetails.find('#field-container-dateOfBirth').hide();
        }
    };

    /**
     * If the profile has data for the field, it will be added as a hidden field with the name of the original input,
     *   so that it will be non-editable but still submittable via the form. The label, original input and container
     *   will all be hidden, too.
     *
     * @param field_name
     *
     * @param value
     *
     * @param field_type
     *
     * @private
     */
    CompetitionForm.prototype._populateAndReplaceField = function (field_name, value, field_type) {
        if (!value.length) { // If the field has no value, don't do anything.
            return;
        }
        var inactive_field_name = field_name + '_inactive'; // What the original input will be renamed to.
        var dummy_field_id = field_name + '_dummy'; // The name for the span for displaying the input value.
        var field_container_id = '#field-container-' + field_name; // The name of the parent element for everything.
        var $element = this.$userDetails.find('*[name="' + field_name + '"]'); // The original input element.

        // Change the name of the original input element and hide it.
        $element.hide().attr({
            name: inactive_field_name,
            disabled: true
        });

        // Add the dummy field (which displays the non-editable value of the field) and the hidden input field with the
        // actual value.
        if (!$('#' + dummy_field_id).length) {
            $('<span class="dummy" id="' + field_name + '_dummy">' + value + '</span>').insertBefore($element);
            $('<input type="hidden" id="' + field_name + '_substitute" value="' + value + '" name="' + field_name + '"/>').insertBefore($element);
        }

        // Hide the parent and all its child elements.
        this.$userDetails.find(field_container_id).hide();

        //if (field_type == 'checkbox') {
        //    this.$form.find('input[name="' + field_name + '"]').prop('checked', value).attr('disabled', false).addClass('disabled');
        //} else if (field_type == 'select') {
        //    this.$form.find('select[name="' + field_name + '"]').val(value).attr('disabled', false).addClass('disabled');
        //} else {
        //    this.$form.find('input[name="' + field_name + '"]').val(value).attr('disabled', false).addClass('disabled');
        //}
    };

    CompetitionForm.prototype._resetField = function (field_name) {
        var $element = this.$userDetails.find('#' + field_name + '_substitute');
        var field_container_id = '#field-container-' + field_name;

        if ($element.length) {
            $element.remove();
            this.$userDetails.find('#' + field_name + '_dummy').remove();
            this.$userDetails.find('*[name="' + field_name + '_inactive"]').show().attr({
                name: field_name,
                disabled: false
            });
            this.$userDetails.find(field_container_id).show();
        } else {
            this.$userDetails.find('*[name="' + field_name + '"]').val('');
        }
    };

    CompetitionForm.prototype.clearForm = function () {
        this._resetField('firstName');
        this._resetField('surname');
        this._resetField('email');
        this._resetField('postCode');
        this._resetField('phone');
        this._resetField('dateOfBirth_day');
        this._resetField('dateOfBirth_month');
        this._resetField('dateOfBirth_year');
        this._resetField('gender');
        this.$userDetails.find('#field-container-dateOfBirth').show();
    };

    CompetitionForm.prototype.authenticated = function (profile) {
        this.$userDetails.show();
        this.populateForm(profile);
        this._triggerSC(profile);
    };

    CompetitionForm.prototype.notAuthenticated = function () {
        this.$userDetails.hide();
        this.clearForm();
    };

    /**
     * Method for use by external scripts to set the derrickFailed variable.
     *
     * @param boolean
     */
    CompetitionForm.prototype.setDerrickFlag = function (boolean) {
        this.derrickFailed = boolean === false ? false : true;
    };

    /**
     * Loop through the array of errors and run the show error function for each.
     *
     * @private
     */
    CompetitionForm.prototype._showErrors = function () {
        for (var error_key in this.errors) { // Loop through the main object's errors array.
            if (this.errors.hasOwnProperty(error_key)) {
                this.showError(error_key, this.errors[error_key]);
            }
        }

        // Scroll to the first error element.
        $('html,body').animate({scrollTop: $('.error').first().offset().top}, 900);
    };

    /**
     * Find the element associated with an error key so that an error message can be inserted before it.
     *
     * @param error_key
     *
     * @returns {boolean}
     *
     * @private
     */
    CompetitionForm.prototype._getErrorElement = function (error_key) {
        var $el = this.$form.find('label[for="' + error_key + '"]'); // First look for a label element.
        if (!$el.length) { // If it doesn't exist, look for the element using this error key as its ID.
            $el = this.$form.find('#' + error_key);
        }

        return !$el.length ? false : $el; // Return false if we haven't found an element for it.
    };

    /**
     * Use the error key to locate the appropriate input element and insert an error message before it.
     * @param error_key
     */
    CompetitionForm.prototype.showError = function (error_key, error_message) {
        if (typeof error_message == 'undefined') {
            error_message = this.errors[error_key];
        }

        // Find the element associated with this error. This will be used as the location to add the error
        // message to.
        var $el = this._getErrorElement(error_key);

        // If neither exist and it's not a 'general' error, output a warning to the console log.
        if (!$el.length && error_key !== 'general') {
            console.log('WARNING: can\'t find parent for ' + error_key);
            return;
        }

        // If there's only one general error, reset the form.
        if (this.errors.length === 2 && error_key === 'general') {
            this.$form[0].reset();
            this._resetPHP();
        }

        // Check if there is a previous error message for this element, remove it if it has changed so that it
        // can be replaced by the new message.
        if (this.$form.find('.error.' + error_key).length) {
            if (this.$form.find('.error.' + error_key).html() === error_message) {
                return;
            }
            this.$form.find('.error.' + error_key).hide('normal').remove();
        }

        // If it's a general error insert the error message before the first fieldset in the form.
        if (error_key === 'general') {
            this.$form.find('fieldset').first().prepend('<p class="error ' + error_key + '">' + error_message + '</p>');
            $('p.' + error_key).hide().show('slow');
            return;
        }

        // If the element is in a nested list, set the target to the parent nested element.
        if ($el.closest('.nested').length) {
            $el = $el.closest('.nested').parent();
        }

        var type = $el.get(0).nodeName.toLowerCase(); // Get the node type of the target element.
        var $parent = $el.parent(); // Get the parent of the target element.
        var parentType = $parent.get(0).nodeName.toLowerCase(); // Get the node type of the parent element.
        var error_node = type; // The type of element the error node will be. Defaults to same as target.
        var error_target = $el; // The element which will have the error node inserted before it.

        // Modify the error_node and error_target values, depending on the target element's type.
        switch (type) {
            // If the target element is a label, target the label's parent and use an element of the same type as the parent.
            case 'label':
                error_node = parentType;
                error_target = $parent;
                break;

            // If the label wasn't assigned and the target element is the actual input field, insert an element of the
            // same type as the closest parent. For inputs within a dd element, make it a dt element.
            case 'input':
                if (parentType === 'dd') {
                    parentType = 'dt';
                }
                error_node = parentType;
                error_target = $parent;
                break;
            case 'dd':
                if ($el.prev().is('dt')) {
                    $el = $el.prev();
                    error_node = 'dt';
                }
                break;
            case 'ul':
            case 'p':
            case 'dt':
            case 'li':
                break;
        }

        var error_el = document.createElement(error_node); // Create an element based on the error node type.
        error_el.className = "error " + error_key; // Add the error and key class to the error node.
        error_el.innerHTML = error_message; // Add the error message to the error node.

        $(error_el).insertBefore(error_target).hide().show('slow'); // Insert the error node before the target element.
    };

    /**
     * Hide and remove an error field.
     *
     * @param $field
     *
     * @private
     */
    CompetitionForm.prototype._removeError = function ($field) {
        var input;
        var className = $field.attr('id');
        if ($field.is(':radio')) {
            className = $field.attr('name');
        }
        if (className.split('_').length > 1) {
            className = className.split('_')[0];
        }

        input = '.error.' + className;
        if (className === 'stationOptIn' || className === 'clientOptIn') {
            input = '.error.email, .error.postCode';
        }

        this.$form.find(input).hide('normal', function () {
            $(this).remove();
        });
    };

    CompetitionForm.prototype.removeAllErrors = function () {
        this.$form.find('.error').hide('normal', function () {
            $(this).remove();
        });
    };

    /**
     * Build the answer length checking functionality.
     *
     * @param object
     *
     * @private
     */
    CompetitionForm.prototype._initAnswerLength = function (object) {
        var $this = $(object), // The label element
            $answer = $('#' + $this.data('target')),// The input id as identified by the label.
            maxVal = parseInt($this.find('span').text()),// The limit as identified in the label text.
            type = ( $this.attr('data-type') === 'words' ? 'words' : 'characters' );

        $this.html('<span></span> ' + type + ' left');
        if ($answer.val().length === 0) {
            $this.find('span').text(maxVal);
        }
        $answer.keyup(function () {
            var answerLength = (type === 'characters' ? $(this).val().length : $answer.val().trim().replace(/\s+/gi, ' ').split(' ').length);
            var charsLeft = maxVal - answerLength;

            var complaintClass = '';
            if (charsLeft < 0) {
                complaintClass = 'less-than-0';
            }
            else if (charsLeft < 30) {
                complaintClass = 'less-than-30';
            }

            $this.addClass(complaintClass).find('span').text(charsLeft);
            $answer.addClass(complaintClass).find('span').text(charsLeft);
        });
    };


    /**
     * Fire the SiteCatalyst tracking events, based on whether the user has logged in, registered or neither of these.
     *
     * @param profile
     *
     * @param Boolean registration
     *
     * @private
     *
     */
    CompetitionForm.prototype._triggerSC = function (profile) {
        var events = [],
            action = '',
            current_logged_in_state = profile ? "signed_in" : "not_signed_in",
            registration_context = "";

        var user_id = !profile ? 'not_signed_in' : profile.id;
        var linkVars = ['prop18', 'prop46', 'eVar18', 'eVar20', 'prop20', 'eVar50', 'events'];

        if (profile.login_event) {
            switch (profile.login_event) {
                case 'emailRegister':
                    events.push("event3", "event9");
                    action = "register";
                    break;
                case 'emailSignIn':
                    events.push("event9");
                    action = "sign_in";
                    break;
                case 'socialSignIn':
                    events.push("event9");
                    action = "sign_in";
                    break;
            }
            registration_context = "MKT:EXT:" + action;
        }
        s.prop18 = current_logged_in_state;
        s.eVar18 = current_logged_in_state;
        s.prop46 = user_id;
        s.eVar50 = user_id;
        s.eVar20 = registration_context;
        s.eProp20 = registration_context;
        s.events = events.join(",");
        s.linkTrackVars = linkVars.join(",");
        s.linkTrackEvents = events.join(",");

        s.tl(true, 'o', 'Commercial Microsite');
    };

    /**
     * Initialise the form object and add it to the window.
     *
     * @param $form The jquery object representing the form.
     *
     * @param $wrapper Optional. The form wrapper element. Defaults to #form-wrapper. Used in _showThankyou function.
     *
     * @param $container Optional. The container element. Defaults to #form-container. Used in _showThankyou function.
     */
    function initForm($form, $wrapper, $container) {
        var competitionForm = new CompetitionForm($form, $wrapper, $container);
        if (typeof derrickInstance !== 'function') {
            competitionForm.derrickFailed = true;
        }
        window.competitionForm = competitionForm;
    }

    window.initForm = initForm;
})
();