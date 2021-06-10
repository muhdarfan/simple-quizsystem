<?php
class Helper {
    public static $Error = Array();
    public static $DB;

    static function LoadClasses() {
        foreach (glob(BASE.'/Library/Classes/*.inc') as $class)
            include_once($class);
    }

    static function Redirect($url = '') {
        header("LOCATION: $url");
    }
}
