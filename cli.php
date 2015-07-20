<?php

use rtens\blog\Admin;
use rtens\domin\cli\CliApplication;

require_once __DIR__ . '/vendor/autoload.php';

CliApplication::run(CliApplication::init(function (CliApplication $app) {
    Admin::initCli($app, __DIR__ . '/data');
}));