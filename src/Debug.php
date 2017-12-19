<?php

namespace dbeurive\Util;

class Debug
{
    static private $__lines = array();
    static private $__activate;

    static public function init($inActivate=true) {
        self::$__activate = $inActivate;
        if (self::$__activate) {
            register_shutdown_function(array(__CLASS__, 'shutdown'));
        }
    }

    static public function addSection($inString) {
        if (self::$__activate) {
            self::$__lines[] = $inString;
        }
    }

    static public function addContent($inString) {
        if (self::$__activate) {
            self::$__lines[] = "\t${inString}";
        }
    }

    static public function shutdown() {
        if (self::$__activate) {
            printf("%s\n", implode("\n", self::$__lines));
        }
    }
}