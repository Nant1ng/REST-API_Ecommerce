<?php

    class DB {
        private static $writeDBConnection;
        private static $readDBConnection;

        // Use WriteDB when you have to insert or change something 
        public static function connectWriteDB() {
            if(self::$writeDBConnection === null) {
                // If you only have 1 DB than you need to put identical values.
                self::$writeDBConnection = new PDO('mysql:host=localhost;dbname=rest_api;utf8', 'root', '');
                self::$writeDBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$writeDBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            }

            return self::$writeDBConnection;
        }
        
        // Use ReadDB when you want to read or view things 
        public static function connectReadDB() {
            if(self::$readDBConnection === null) {
                // If you only have 1 DB than you need to put identical values.
                self::$readDBConnection = new PDO('mysql:host=localhost;dbname=rest_api;utf8', 'root', '');
                self::$readDBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$readDBConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            }

            return self::$readDBConnection;
        }
    }
?>