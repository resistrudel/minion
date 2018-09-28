<div class="finish" id="finish">
	<h1><?= $gamedata['finish'][$score_level]['title'] ?></h1>

	<div class="finish-text"><?= formatText($finish_text) ?></div>
</div>
<?php if (!$competition_entered && !$gamedata['inactive']) { ?>
	<div class="enter-container">
		<p><?= $gamedata['finish'][$score_level][$restart_text_key] ?></p>
		<button class="cta restart" id="restart">Play Again</button>
		<p><?= $gamedata['finish'][$score_level][$enter_text_key] ?></p>
		<button class="cta enter" id="enter">Enter</button>
	</div>
<?php } else { ?>
	<div class="restart-container">
		<p><?= $gamedata['finish'][$score_level][$restart_text_key] ?></p>
		<button class="cta restart" id="restart">Play Again</button>
	</div>
<?php } ?>