<?php
require_once('includes/common.constants.php');
include('includes/form.validator.php');
include('includes/templ.header.php');

// Specify the form encoding type if it needs to handle file uploads.
$form_multipart = isset($text['competition']['fileUpload']) && $text['competition']['fileUpload'] ? 'enctype="multipart/form-data" ' : '';

// Determine the competition status based on the timestamp and the 'submitted' session value.
$competition_status =
    ($timestamp >= strtotime($text['competition']['ends']) ? 1 : //ended
        ($timestamp < strtotime($text['competition']['starts']) ? 2 : //coming
            (isset($_SESSION[$campaign][$formID]['submitted']) && $_SESSION[$campaign][$formID]['submitted'] == 1 ? 3 : 4))); // submitted || on

?>
    <section class="intro">
        <div class="wrapper">
            <header role="banner">
                <div class="header-wrapper">
                    <a id="brandLogo" class="brand-logo" href="<?= $brands[$brand]['brandURL']; ?>"
                       target="_blank"><?= $brands[$brand]['slogan']; ?></a>

                    <a id="clientLogo" class="client-logo" href="<?= $client['link']; ?>"
                       target="_blank"><?= $client['slogan']; ?></a>
                </div>
            </header>
            <div class="text-box">
                <h1><?= $text['intro']['title'] ?></h1>
                <?= formatText($text['intro']['text']) ?>
                <?= formatText($text['intro']['link-text'], 'cta', array('href'=>'#competition', 'class'=>'scroll')) ?>
                <p class="watch"><a class="scroll" href="#video">Watch the trailer</a></p>
            </div>
        </div>
    </section>

<div class="minions"></div>

<?php if (isset($text['video'])) { ?>
    <section id="video" class="video">
        <div class="wrapper">
            <div class="text-box">
                <h3><?= $text['video']['title'] ?></h3>

                <div class="copy">
                    <?= formatText($text['video']['text']) ?>
                </div>
                <p class="iframe">
                    <iframe src="<?= $text['video']['link'] ?>?api=1" frameborder="0"
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </p>
            </div>
        </div>
    </section>
<?php } ?>

    <section class="tickets">
        <div class="wrapper">
            <div class="text-box">
                <h1><?= $text['tickets']['title'] ?></h1>
                <?= formatText($text['tickets']['text']) ?>
                <p class="cta"><a href="<?=$text['tickets']['link-url']?>" target="_blank"><?=$text['tickets']['link-text']?></a></p>
            </div>
        </div>
    </section>




    <div class="competition" id="competition">
        <?php if ($competition_status === 4) {
        $error_output = isset($error['general']) ? '<p class="error">' . $error['general'] . '</p>' : '';
        ?>
        <form role="form" action="<?= $siteDetails['baseURL'] ?>#competition" id="competition-form" method="post" class="clearfix"
            <?= $form_multipart ?>  novalidate="novalidate">
            <section class="quiz">
                <div class="wrapper">
                    <div class="quiz-container">
                        <div class="text-box quiz-intro">
                            <h1><?= $text['quiz']['title'] ?></h1>
                            <?= formatText($text['quiz']['text']) ?>
                        </div><!--end textbox-->

                        <div class="items-container" id="items-container">
                            <?php foreach ($text['quiz']['items'] as $idx => $item) { ?>
                                <div class="item inactive" data-index="<?= $idx ?>">
                                    <h3>Question <?= $idx+1 ?></h3>
                                    <?= formatText($item['question']) ?>
                                    <ul class="item-options">
                                        <?php foreach ($item['options'] as $optx => $option) { ?>
                                            <li data-answer-val="<?= $optx ?>" data-question-id="<?= $idx ?>">
                                                <button type="button"><?= $option ?></button>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                            <div class="results">
                                <h2><?= $text['quiz']['score']['title'] ?></h2>
                                <h3><?= $text['quiz']['score']['text'] ?></h3>
                                <button type="button" class="cta restart" id="restart">Play Again</button>
                                <button type="button" class="cta enter" id="enter">Enter Now</button>
                            </div>
                        </div>

                    </div> <!--end quiz-container-->
                </div> <!--end wrapper-->
                <div class="question" id="question">
                    			<div class="wrapper question-wrapper">
                    <fieldset id="competitionQuestion" class="competition-question">
                        <div class="text-box selectPrize">
                            <h2><?= $text['competition']['title'] ?></h2>
                            <?= formatText($text['competition']['text']) ?>
                            <?php
                            echo $error_output;
                            if (isset($text['competition']['answers']) && is_array($text['competition']['answers'])) { ?>
                                <div class="question-container">
                                    <p id="answer"><?= $text['competition']['question'] ?></p>
                                </div>
                                <div class="answers-container">
                                    <ul>
                                        <?php
                                        foreach ($text['competition']['answers'] as $idx => $val) {
                                            echo $form->answerElement($idx, $val);
                                        } ?>
                                    </ul>
                                </div>
                            <?php
                            } else {
                                if (!isset($text['competition']['question'])) {
                                    goto end2;
                                }
                                ?>
                                <p class="full">
                                    <label for="answer"><?= $text['competition']['question'] ?></label>
                                </p>
                                <p>
                                    <textarea id="answer" name="answer"><?= $form->getValue('answer') ?></textarea>
                                </p>
                                <?php
                                if (!isset($text['competition']['answerLimit'])) { ?>
                                    <p>Set the <b>answerLimit</b> variable in the content file.</p>
                                    <?php
                                    goto end2;
                                }
                                if (!isset($text['competition']['answerLimitType'])) { ?>
                                    <p>Set the <b>answerLimitType</b> variable in the content file.</p>
                                    <?php
                                    goto end2;
                                }
                                ?>
                                <p class="answer-length" id="answerLength" data-target="answer"
                                   data-type="<?= $text['competition']['answerLimitType'] ?>">Please limit your answer
                                    to <span><?= $text['competition']['answerLimit'] ?></span>
                                    <?= $text['competition']['answerLimitType'] ?> or less</p>
                            <?php }
                            end2: ?>
                        </div>
                    </fieldset>
                    			</div>
                </div>
            </section> <!--end quiz section-->
            <?php } ?>
            <div id="form-wrapper" class="form-wrapper <?= $competition_status === 3 ? 'thank-you' : '' ?>">
                <?php
                if ($competition_status === 1) { ?>
                    <div class="wrapper">
                        <div class="column">
                            <p>Sorry! The competition is over already... For more chances to win, check out the <a
                                    href="<?= $brands[$brand]['brandURL'] ?>"><?= $brands[$brand]['name'] ?> website</a>
                        </div>
                    </div>
                    <?php
                    goto exitForm;
                } else if ($competition_status === 2) { ?>
                    <div class="wrapper">
                        <div class="column">
                            <p>Hold your horses! Competition starts
                                at <?= date('G:i \o\n jS F Y', strtotime($text['competition']['starts'])) ?></p>
                        </div>
                    </div>
                    <?php
                    goto exitForm;
                }
                if ($competition_status === 3) {
                    include('includes/templ.success.php');
                } else {
                    include('includes/form.competition.php');
                }
                exitForm:
                ?>
            </div>
            <?php if ($competition_status === 4) { ?>
        </form>
    <?php } ?>
    </div> <!-- end competition -->
<?php
end:
include('includes/templ.footer.php');