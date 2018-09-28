<?php
require_once('common.constants.php');
echo formatText($content[$page]['competition']['thank-you']);

$twitter = isset($text['twitterMsg']) && !empty($text['twitterMsg']);
$facebook = isset($text['twitterMsg']) && !empty($text['twitterMsg']);

if ($twitter || $facebook) {
	$social_class = $twitter && $facebook ? ' full' : ' half';
	?>
	<div id="social" class="clearfix <?= $social_class ?>">
	<?php
	if ($twitter) {
		$twitter_link = isset($text['shortLink']) && !empty($text['shortLink']) ? $text['shortLink'] : $siteDetails['baseURL'];
		?>
		<div class="twitter box">
			<div>
				<a class="twitter-share-button" href="http://twitter.com/share" data-url="<?= $twitter_link ?>"
				   data-text="<?= $text['twitterMsg'] ?>">Tweet</a>
			</div>
		</div>
	<?php }
	if ($facebook) { ?>
<div class="facebook box"><div><div class="fb-share-button left" data-href="', $siteDetails['baseURL'], '" data-type="button_count"></div></div></div>';
	<?php }
	echo '</div>';
}

// End of file: templ.success.php