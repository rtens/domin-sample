<?php

use rtens\blog\Admin;
use rtens\blog\web\IndexResource;
use rtens\domin\delivery\web\WebApplication;
use watoki\curir\WebDelivery;

require_once __DIR__ . '/vendor/autoload.php';

WebDelivery::quickResponse(IndexResource::class,
    WebDelivery::init(null,
        WebApplication::init(function (WebApplication $app) {
            Admin::initWeb($app, __DIR__ . '/data');
        })));