<?php
// Common site functions
function autoVersion($file)
{
    $path = realpath(dirname($file));
    if (!file_exists($path)) {
        return $file;
    }
    $mtime = filemtime($path);
    return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}

// Form handling functions
function safeChecks($str)
{
    return htmlspecialchars(addslashes(trim($str)));
}

function specialChars(&$text)
{
    $p[0] = '“';
    $r[0] = '"';
    $p[1] = '”';
    $r[1] = '"';
    $p[2] = '‘';
    $r[2] = '\'';
    $p[3] = '’';
    $r[3] = '\'';
    $p[4] = '&';
    $r[4] = '&amp;';
    //$p[5]='-'; $r[5]='&mdash;';
    $p[5] = '£';
    $r[5] = '&pound;';

    $text = str_replace($p, $r, $text);
}

function formatText(&$text, $wrapper = 'p', $arg = array())
{
    global $siteDetails;
    if (!isset($text)) return;

    // special cases
    switch ($wrapper) {

        case 'img':
            $regexUrl = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            $output = '<p'.(isset($arg['containerId']) ? ' id="'.$arg['containerId'].'"' : '').' class="img'.(isset($arg['containerClass']) ? ' '.$arg['containerClass'] : '').'"><img'.(isset($arg['id']) ? ' id="'.$arg['id'].'"' : '').(isset($arg['class']) ? ' class="'.$arg['class'].'"' : '').' src="'.( preg_match($regexUrl, $text) ? $text : $siteDetails['baseURL'].( isset($arg['folder'])? $arg['folder'] : 'img/').$text).'" /></p>';
            break;

        case 'iframe':
            $regexUrl = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            $src = ( preg_match($regexUrl, $text) ? $text : // facebook or full URL
                ( ctype_digit($text) ? 'https://player.vimeo.com/video/'.$text : // vimeo id contains only numbers
                    'https://www.youtube.com/embed/L0MK7qz13bU'.$text )); // youtube id

            $output = '<div'.(isset($arg['containerId']) ? ' id="'.$arg['containerId'].'"' : '').' class="iframe'.(isset($arg['containerClass']) ? ' '.$arg['containerClass'] : '').'">
            <iframe'.(isset($arg['id']) ? ' id="'.$arg['id'].'"' : '').(isset($arg['class']) ? ' class="'.$arg['class'].'"' : '').' src="'.$src.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>';
            break;

        case 'cta':
            $output = '<p class="cta"><a'.(isset($arg['id']) ? ' id="'.$arg['id'].'"' : '').(isset($arg['class']) ? ' class="'.$arg['class'].'"' : '').' href="'.(isset($arg['href']) ? $arg['href'] : '#').'">' . $text . '</a></p>';
            break;

        default:
            $regexURL = '@^[href="]http://(([\w-.]+)+(:\d+)?(/([\w/_.\-]*(\?\S-+)?)?)?)@';

            $p[0] = $regexURL;
            $r[0] = '<a href="$1" target="_blank">$1</a>';

            $p[1] = '@#\[(\d)\](.*?)#@';
            $r[1] = '</p><h$1>$2</h$1><p>';

            $p[2] = '@\(\)(.*?)\n@';
            $r[2] = '</p><ol><li>$1</li></ol><p>';

            $p[3] = '@\<p\>(\n*\r*\s*)\<\/p\>@';
            $r[3] = '';

            $p[4] = '@\<\/ol\>(\n*\r*\s*)\<ol\>@';
            $r[4] = '';

            $p[5] = '@\<p\>(\n*\r*\s*)\<br\s*\/*\>@';
            $r[5] = '<p>';

            $p[6] = '@(?:<br\s* /?>\s*?){2,}@';
            $r[6] = '</p><p>';

            $p[7] = '@\~\[(\S+)\](.*?)\~@';
            $r[7] = '<$1>$2</$1>';

            $p[8] = '@\<p\>(\n*\r*\s*)\%(.*?)\%@';
            $r[8] = '<p class="$2">';

            $output = '<'.$wrapper.(isset($arg['id']) ? ' id="'.$arg['id'].'"' : '').(isset($arg['class']) ? ' class="'.$arg['class'].'"' : '').( $wrapper == 'a' ? ' href="'.( isset($arg['href']) ? $arg['href'] : '#').'"' : '').'>' . preg_replace($p, $r, nl2br($text)) . '</'.$wrapper.'>';
            $output = preg_replace($p[3], $r[3], $output);

            specialChars($output);

            $output = preg_replace($p[5], $r[5], $output);
            break;
    }

    return $output;
}

function findContent($time)
{
    global $siteDetails;
    // RE-CACHE THE LIST EVERY 24 HOURS
    // MOST OF THE TIME IT WILL BE THE SAME,
    // BUT WE DON'T WANT TO MISS IF WE ADDED NEW CONTENT FILE
    // USUALLY AMENDS ARE DONE MORE THAN 24 HOURS IN ADVANCE, SO WE SHOULD BE FINE
    $cacheInterval = 24 * 60 * 60;

    $contentListFile = $siteDetails['localPath'] . 'cache/contentList.php';

    // IF FILE EXISTS AND IT'S NEWER THAT 24 HOURS, TAKE THE LIST FROM THE FILE
    $contentList = (file_exists($contentListFile) && time() - $cacheInterval > filemtime($contentListFile) ? unserialize(file_get_contents($contentListFile)) : getFolderList($contentListFile));

    // IF FILE EXISTS, BUT THE ARRAY IS EMPTY, THAT CAN MEAN ONLY ONE THING - THE DEFAULT COPY IS ON!
    if (empty($contentList)) {
        return false;
    }

    // SEARCH IS EASIER IF DONE FROM THE END
    $length = count($contentList) - 1;
    for ($i = $length; $i >= 0; $i--) {
        if ($time >= $contentList[$i]) {
            return $contentList[$i];
        }
    }

    // IF FILE WAS FOUND AND ARRAY NOT EMPTY, BUT STILL NOTHING FOUND,
    // THAT CAN ONLY MEAN THE FIRST CHANGES ARE STILL DUE
    return false;
}

function getFolderList($cacheFile = '')
{
    global $siteDetails;

    $files = array();
    $dir = scandir($siteDetails['localPath'] . 'content/', 1);
    foreach ($dir as $fileName) {
        // IF SYSTEM FILE - SKIP
        if ($fileName == '.' || $fileName == '..') {
            continue;
        }
        $fileName = str_replace('.php', '', $fileName);

        // IF WRONG DATE TYPE - SKIP
        $fileTime = strtotime($fileName);
        if (!$fileTime || $fileTime == 0) {
            continue;
        }

        // ADD TO THE LIST
        $files[$fileTime] = $fileName . '.php';
    }

    // ARRANGE DATA BY NAME
    ksort($files);

    if (empty($cacheFile)) return $files;

    $contentList = array();
    foreach ($files as $timestamp => $fileName) {
        array_push($contentList, $timestamp);
    }

    file_put_contents($cacheFile, serialize($contentList));
    return $contentList;
}

function getContent($timestamp)
{
    global $siteDetails, $content, $preview;

    $contentFile = $siteDetails['localPath'] . 'cache/' . date('Y-m-d H.i', $timestamp) . '.php';

    // IF FILE WAS CACHED, GET IT FROM FILE AND BE DONE WITH IT
    if (file_exists($contentFile)) {
        $content = unserialize(file_get_contents($contentFile));
        return $content;
    }

    // NOW IF IT'S NOT FOUND, WE HAVE TO CREATE IT
    $contentFolder = $siteDetails['localPath'] . 'content/';

    $files = getFolderList();

    global $brands, $links, $brand;

    foreach ($files as $t => $fileName) {
        if ($t <= $timestamp) {
            include($contentFolder . $fileName);
        }
    }

    file_put_contents($contentFile, serialize($content));

    return $content;
}

// FOR TWITTER
function twitterFeed($search, $n)
{
    global $siteDetails, $brands, $brand;

    // Grab cached version
    $cacheFile = $siteDetails['localPath'] . 'cache/twitter-' . $search . '.php';
    $cacheTime = 5 * 60;
    if (file_exists($cacheFile) && (time() - $cacheTime < filemtime($cacheFile))) {
        echo '<!-- From cache generated ', date('H:i', filemtime($cacheFile)), ' -->';
        include($cacheFile);
        return;
    }

    require_once('library/class.twitter.php');

    $connection = new TwitterOAuth($brands[$brand]['twitterAPI']['key'], $brands[$brand]['twitterAPI']['secret']);
    $json = $connection->get('https://api.twitter.com/1.1/search/tweets.json?lang=en&q=' . urlencode($search) . '&src=typd&count=' . ($n * 10));

    $pod = '<ul id="tweets">';
    $k = 0;
    $j = 0;
    foreach ($json->statuses as $i) {
        if (isset($i->retweeted_status)) {
            continue;
        } else {
            $j++;
        }
        $text = preg_replace('@(http://([\w-.]+)+(:\d+)?(/([\w/_.\-]*(\?\S-+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', trim($i->text));
        //$author=trim($i->user->name);
        $username = '@' . trim($i->user->screen_name);
        $url = 'http://www.twitter.com/' . $username;
		$user_img = $i->user->profile_image_url;
        $time = strtotime($i->created_at);

        $pod .= '<li><span class="user-img"><img class="src" src="' . $user_img . '" alt="' . $username . '"/></span><p><span class="author"><a href="' . $url . '">' . $username . '</a></span><span class="date">&nbsp;(' . timeAgo($time) . ')</span><br /> ' . nl2br($text) . ' </p></li>';
        $k++;

        if ($j >= $n) {
            break;
        }
    }
    $pod .= '</ul>';

    // Create cache file
    $fp = fopen($cacheFile, 'w');
    fwrite($fp, $pod);
    fclose($fp);

    include($cacheFile);
}

function timeAgo($time)
{
    $timeSpan = time() - $time;
    if ($timeSpan < 60) {
        return 'less than a minute ago';
    }
    $timeSpan = round($timeSpan / 60);
    if ($timeSpan < 60) {
        return $timeSpan . ' minute' . ($timeSpan % 10 == 1 && $timeSpan != 11 ? '' : 's') . ' ago';
    }
    $timeSpan = round($timeSpan / 60);
    if ($timeSpan < 24) {
        return $timeSpan . ' hour' . ($timeSpan % 10 == 1 && $timeSpan != 11 ? '' : 's') . ' ago';
    }
    $timeSpan = round($timeSpan / 24);
    return $timeSpan . ' day' . ($timeSpan % 10 == 1 && $timeSpan != 11 ? '' : 's') . ' ago';
}

function echoCaptcha()
{
    global $security, $error, $siteDetails;
    echo '<dl>', (isset($error['captcha']) ? '<dt class="error">' . $error['captcha'] . '</dt>' : ''), '<dt class="img">', $security->getCaptchaImage($siteDetails['localPath']), '</dt>
            <dt><label for="captcha">Re-type:</label> *</dt>
            <dd', (isset($error['captcha']) ? ' class="error"' : ''), '><input type="text" id="captcha" name="captcha" required /></dd>
            </dl>';
}
