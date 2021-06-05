<?php
class Helper {
    static $DB;

    static function LoadClasses() {
        foreach (glob(BASE.'/Library/Classes/*.inc') as $class)
            include_once($class);
    }

}
