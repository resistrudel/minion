<?php
// default content
$content = array(
	'home' => array(
		'metaTitle' => 'Heart | Universal Pictures',
		'metaKeywords' => 'Despicable Me, win, competition, minion, adventure',
		'metaDescription' => 'Win A Brilliant Family Adventure with Despicable Me 3 ',
		'intro' => array(
			'title' => 'Win A Brilliant Family Adventure with</br>Despicable Me 3 ',
			'text' => <<<TEXT
Gru and the crew are back for Despicable Me 3 – and you could be celebrating with a fantastic family outing.

You get to choose between a spectacular sunrise hot air balloon ride or a thrilling speed-boat ride – both with an overnight stay at a 4-star hotel.

Find out more about the latest must-see family movie below before getting your entry in for one sensational prize!

<a target="_blank" href="http://www.facebook.com/DespicableMeUK">Visit Despicable Me 3 on Facebook</a>
TEXT
		,
			'link-text' => 'enter now'
		),

		'video' => array(
			'title' => 'Trailer',
			'text' => <<<TEXT
TEXT
			,
			'link' => 'https://www.youtube.com/embed/g0DFXa6fdDc?list=PLpTga61DBp6D4kWmu1d-RjMWhjsPZ7fSY'
		),

        'tickets' => array(
            'title' => 'Despicable Me 3 ',
            'text' => <<<TEXT
Gru and the crew are back for Despicable Me 3, but this time Gru, Lucy, their adorable daughters - Margo, Edith and Agnes - and the Minions are joined by Gru’s brother, Dru and 80s child star supervillain, Balthazar Bratt.

Starring the voice talents of Steve Carell, Kristen Wiig and Grammy Award Winner Trey Parker, Despicable Me 3 is in cinemas right now!

Parker voices the role of villain Balthazar Bratt, a former child star who’s grown up to become obsessed with the character he played in the ‘80s, and proves to be Gru’s most formidable nemesis to date.

©2017. Universal Studios.
TEXT
        ,
            'link-text' => 'Book tickets',
            'link-url' => 'http://www.dm3tickets.co.uk '
        ),

        'quiz' => array(
            'id' => 'minion',
            'title' => 'Win A Family Prize ',
            'text' => <<<TEXT
To be in with a chance of winning either a hot air balloon trip at sunrise and overnight stay – or a speed boat ride experience and overnight stay in London, just answer the question below and tell us your choice of prize.

Entries close at 23.59 on Thursday 13th July 2017.
TEXT
        ,
            'link-text' => 'click to enter',

             'items' => array(
                    array(
                        'question' => 'In the trailer, complete the phrase that Dru says to Gru – “Your discomfort is…… ',
                        'options' => array(
                            'Fabulous',
                            'Hilarious',
                            'Obvious',
                        ),
                        'correct-option' => 2
                    ),
                    array(
                        'question' => 'In the trailer, which animal’s horn do the girls discover?',
                        'options' => array(
                            'Buffalo',
                            'Rhino',
                            'Unicorn',
                        ),
                        'correct-option' => 3
                    ),
                    array(
                        'question' => 'In the trailer, what colour suit does Dru wear?',
                        'options' => array(
                            'Blue',
                            'Green',
                            'White',
                        ),
                        'correct-option' => 3
                    ),
            ),
            'score' => array(
                'title' => 'Congratulations',
                'text' => "You scored <span id='score'>[*score*]</span> out of a possible <span id='total'>[*total*]</span>!",
                'enter-text' => 'Enter Now',
                'restart-text' => 'Play again',
            ),
   ),


		'competition' => array(
			'starts' => '2015-02-01 00:00:00',
			'ends' => '2017-07-13 23:59:59',
			'name' => 'despicableMe2017',
			'title' => 'Which prize would you prefer? ',
			'text' => "",
			'terms' => 'http://www.heart.co.uk/terms-conditions/heart-network-despicable-me-3-online/',
			'question' => '',
			'answers' => array(
				'Hot Air Balloon Trip', 'Thames Speed Boat Ride'
			),
			'fileUpload' => false,

			'thank-you' => '#[3]Thank you!#

                You successfully entered the competition. Good luck!'
		),
	)
);

// End of file: common.content.php