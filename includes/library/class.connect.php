<?php
    class myPDO extends PDO {
        const DATABASE = 'competitions2017';
        const USER = 'compmanager';
        const PASS = 'C0mp3t1t10n!';

        public function __construct() {
            parent::__construct('mysql:host=localhost;dbname='.self::DATABASE.';charset=utf8', self::USER, self::PASS);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    }
?>