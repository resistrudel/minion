/**
 * Function for scrolling to a link.
 *
 * @param $el jQuery object representing the scroll link.
 */
function scrollToLink($el) {
    var full_url = $el.href, parts = full_url.split("#");
    $('html,body').animate({scrollTop: $("#" + parts[1]).offset().top}, 900);
}

/**
 * Get the value of a specific url parameter.
 *
 * @param variable name of the url parameter.
 */
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] === variable) {
            return pair[1];
        }
    }
    return false;
}

/**
 * Check that the derrick object is present and that the user is logged in. Used in place of the standard
 *   derrick.hasProfile() method, as it causes an error if derrick isn't defined.
 *
 * @returns {boolean}
 *
 * @private
 */
function derrickPresentAndLoggedIn() {
    var result = false;
    if (typeof derrick !== 'undefined') { //
        if (derrick.Derrick.prototype.hasProfile()) {
            result = true;
        }
    }
    return result;
}