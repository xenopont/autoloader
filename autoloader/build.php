<?php
require_once(__DIR__.'/classes/builder.php');

chdir(__DIR__.'../');

$builder = new \Autoloader\Builder();
$builder->run();
