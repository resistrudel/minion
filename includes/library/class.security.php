<?php
    class Security {
        private static $localSalt=array(
            'Ac-ci_o!',
            'Aug-a_men-ti!',
            'Asc-e-n_di-o!',
            'Con-frin-go!',
            'Cr-u-ci-o!',
            'De-pul-so!',
            'Ex-pec-to_Pat-ro-num!',
            'Exp-ell-ia_rmus!',
            'Lev_i_corpus!',
            'Tar-an-tal_leg-ra!'
        );

        private static $minTime=1; // 10 seconds
        private static $maxTime=1800; // 30 minutes
        private static $timeOutInterval=60;

        private $timeStamp = null;
        private $timeOutScore = 0;
        private $timeOut = 10;

        private $phpToken = '';
        private $jsToken = '';
        private $captcha = '';
        private $error = '';

        private $showErrors = false;
        private $checkCaptcha = true;

        public function __construct($brand, $project) {
          //  $this->timeStamp = time();
            $this->timeOut = self::$minTime;

            $this->phpToken = $this->createRandomToken();

            $this->phpLabel = 't_'.$brand.'_'.$project.'-'.octdec(substr(md5($project.self::$localSalt[0]), 0, 8));
            $this->jsLabel = 'j_'.$brand.'_'.$project.'-'.octdec(substr(md5($project.self::$localSalt[2]), 0, 8));
        }

        private function createRandomToken() {
            $localCount=count(self::$localSalt)-1;
            $token=md5(rand(100, 1000).self::$localSalt[rand(0, $localCount)]).md5(date('NdzWntHisu')).rand(1000, 2000);
            return $token;
        }

        public function updateTimeToken($str='') {
            if (!empty($str)) { $this->timeStamp = strtotime($str); return; }
            $this->timeStamp = time();
        }
        public function updatePhpToken() {
            $this->phpToken = $this->createRandomToken();
            return $this->phpToken;
        }
        public function updateJsToken() {
            $this->checkCaptcha = false;
            $this->jsToken = $this->createRandomToken();
            return $this->jsToken;
        }

        public function getPhpToken() { return $this->phpToken; }
        public function getJsToken() { return $this->jsToken; }

        public function getPhpLabel() { return $this->phpLabel; }
        public function getJsLabel() { return $this->jsLabel; }

        public function getErrorMsg() {return $this->error; }

        public function updateCaptcha() {
            $this->captcha='';
            for ($i=0; $i<8; $i++) {  $this->captcha.=chr(mt_rand(97, 122)); }
            $break=rand(0, 7);
            $c1=substr( $this->captcha, 0, $break);
            $c2=substr( $this->captcha, $break+1);
            $this->captcha=$c1.mt_rand(2,99).$c2;
        }
        public function getCaptchaImage($path) {
            ob_start();

            // Create image
            $sizeX=380;
            $sizeY=100;
            $image=imagecreatetruecolor($sizeX, $sizeY);
            $imgBG=imagefilledrectangle($image,0,0,$sizeX,$sizeY,imagecolorallocate($image, 228, 238, 238));

            // Add noise
            $noise=rand(0,2);
            for ($i=0; $i<$noise; $i++) {
                $c=mt_rand(142, 180);
                $r=mt_rand(3, 6);
                $color=imagecolorallocate($image, $c, $c, $c);
                imagefilledellipse($image, mt_rand(0,$sizeX), mt_rand(0,$sizeY), $r, $r, $color);
            }

            $noise=rand(0,2);
            for ($i=0; $i<$noise; $i++) {
                $c=mt_rand(142, 180);
                $color=imagecolorallocate($image, $c, $c, $c);
                imageline($image, mt_rand(0,$sizeX), mt_rand(0,$sizeY), mt_rand(1,$sizeX), mt_rand(1,$sizeY), $color);
            }

            // Add letters
            $textX=rand(0,20);
            $captcha=str_split( $this->captcha);
            foreach ( $captcha as $i) {
                $angle=rand(-5, 5);
                $fontSize=rand(14, 24);
                $textX=$textX+rand(12,50);
                $textY=rand((int)(1.25*$fontSize), (int)($sizeY-0.2*$fontSize));
                $c=mt_rand(142, 180);
                $color=imagecolorallocate($image, $c, $c, $c);
                imagettftext($image, $fontSize, -$angle, $textX, $textY, $color, $path.'/fonts/Courier New.ttf', $i);
            }
            imagepng($image);
            $img = sprintf('<img src="data:image/png;base64,%s"/>', base64_encode(ob_get_clean()));
            imagedestroy($image);
            return $img;
        }
        public function spillTheBeans() { return $this->captcha; }

        public function switchOnErrors() { $this->showErrors = true; return; }
        public function checkTokens() {

            //error_log(print_r($this,1),0);
            // CHECK IF FORM IS SUBMITTED WITHIN TIMEFRAME
            $postTime = time() - $this->timeStamp;

            $err='tooFast';

            if ( $postTime < $this->timeOut ) {
                $this->timeOutScore++;
                $this->timeOut = $this->timeOutScore * self::$timeOutInterval;

                $this->setError('Before min time - '.$postTime);
                return $err;
            }

            $err='tooSlow';
            if ( $postTime > self::$maxTime ) { $this->setError('After max time - '.$postTime); return $err; }

            // CHECK IF PHP TOKEN MATCH
            $err = 'noCSRF';
            $phpToken = (isset($_POST[$this->phpLabel]) ? safeChecks($_POST[$this->phpLabel]): '');
            if ( empty($phpToken) || empty($this->phpToken)) { $this->setError('No PHP token'); return $err; }
            if ( $phpToken != $this->phpToken ) { $this->setError('PHP token doesn\'t match'); return $err; }

            // CHECK CAPTCHA
            if ($this->checkCaptcha) {

                // THE SWITCH TO CHECK CAPTCHA IS STILL ON, BUT THERE'S NO CAPTCHA IN SESSION
                if ( empty($this->captcha) ) {$this->setError('No JS, no CAPTCHA'); return $err;}

                // THE CAPTCHA IN POST IS EMPTY
                $err = 'enterCaptcha';
                $captcha = (isset($_POST['captcha']) ? safeChecks($_POST['captcha']): '');
                if ( empty($captcha) ) { return $err; }

                // THE CAPTCHA DOES NOT MATCH
                $err = 'captchaDoesntMatch';
                if ( $captcha != $this->captcha ) { return $err; }
            }
            // CHECK IF JS TOKEN MATCH
            else {
                $jsToken = (isset($_POST[$this->jsLabel]) ? safeChecks($_POST[$this->jsLabel]): '');
                if ( empty($jsToken) || empty($this->jsToken)) { $this->setError('No JS token'); return $err; }
                if ( $jsToken != $this->jsToken ) { $this->setError('JS token doesn\'t match'); return $err; }
            }

            return false;
        }

        private function setError($err) {
            $this->error = $err;
            if ($this->showErrors) { echo '<!-- ', $err, '-->'; }
            error_log($this->error,0);
        }
    }
?>