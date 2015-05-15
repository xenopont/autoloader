<?php
require_once(__DIR__.'/classes/builder.php');

//chdir(__DIR__.'/..');

$settings = include(__DIR__.'/settings.php');
$builder = new \Autoloader\Builder(__DIR__.'/..', $settings['builder']);
$builder->run();
