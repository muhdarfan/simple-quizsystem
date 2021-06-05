<?php
define('BASE', dirname(__FILE__));

session_start();

require 'Library/helper.php';
require 'Library/config.php';

Helper::LoadClasses();

try {
    Helper::$DB = new PDO("mysql:{$Config['mysql']['host']};dbname={$Config['mysql']['db']};charset=UTF8", $Config['mysql']['user'], $Config['mysql']['pass']);
    Helper::$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($e->getMessage());
}
