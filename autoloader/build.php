<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once(__DIR__.'/classes/builder.php');

$settings = include(__DIR__.'/settings.php');
$builder = new \Autoloader\Builder(__DIR__.'/..', $settings['builder']);
$builder->run();
