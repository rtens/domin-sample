<?php

use rtens\blog\Admin;
use rtens\domin\cli\CliApplication;

require_once __DIR__ . '/vendor/autoload.php';

$read = function ($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
};

$write = function ($string) {
    echo $string;
};

CliApplication::run(Admin::initCli(__DIR__ . '/data'), $read, $write);