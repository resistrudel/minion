<?php
include_once('inc.init.php');

// Answer processing.
if (isset($_POST['answer']) && isset($_POST['question'])) {
    $_SESSION[$game_id]['answered'] += 1; // Increment the number of questions answered.
    $response = array(); // Empty array to be populated with the
    $answer = stripslashes($_POST['answer']);
    $question = $questions[$_POST['question']];
    $correct_option = $question['correct-option'];
    $result = $answer === $correct_option ? 'correct' : 'incorrect';
    $next_id = $_POST['question'] + 1;
    $next = array_key_exists($next_id, $questions) ? $next_id : false;
    if ($result === 'correct') {
        $_SESSION[$game_id]['score'] += 1;
    }

    // Send back all the data needed for proceeding with the quiz.
    $response['title'] = $gamedata['feedback'][$result]; // Feedback title.
    $response['message'] = $question['feedback']; // Feedback message.

    // We're not showing the full version of the image now.
    //$response['fullImage'] = $gamesdir . $game_id . '/img/' . $question['images']['full']; // The response image.
    $response['result'] = $result; // correct | incorrect
    $response['next'] = $next; // The next item to display.
    $response['correctItem'] = $correct_option; // The index of the correct answer for this question.
    $response['answered'] = $_SESSION[$game_id]['answered'];
    if ($_SESSION[$game_id]['answered'] == $question_count) { // Indicate if it's the last question.
        $response['finished'] = true;
        $response['score'] = $_SESSION[$game_id]['score'];
    }
    echo json_encode($response); // Output the JSON response.
}

if (isset($_POST['finish'])) {
    switch ($_SESSION[$game_id]['score']) {
        case '4':
        case $question_count:
            $score_level = 'perfect';
            $_SESSION[$game_id]['perfect_score'] = true;
            break;
        case '2':
        case '3':
            $score_level = 'almost-there';
            break;
        default:
            $score_level = 'try-again';
            break;
    }
    $game_url = $siteDetails['baseURL'] . 'games/' . $gamedata['id'];
    $finish_search_array = array('[*score*]', '[*total*]'); // Array of items to be replaced in the finish text.
    $finish_replace_array = array($_SESSION[$game_id]['score'], $question_count); // Array of items to insert into the finish text.
    $finish_text = str_replace($finish_search_array, $finish_replace_array, $gamedata['finish'][$score_level]['text']);
    $twitter_text = str_replace($finish_search_array, $finish_replace_array, $gamedata['finish']['twitter-text']);
    $facebook_text = str_replace($finish_search_array, $finish_replace_array, $gamedata['finish']['facebook-text']);
    $restart_text_key = $gamedata['inactive'] ? 'inactive-restart-text' : 'restart-text';
    $enter_text_key = $gamedata['inactive'] ? 'inactive-enter-text' : 'enter-text';
    include_once('inc.finish.php');
}