<?php

use rtens\blog\admin\Admin;
use rtens\blog\web\IndexResource;
use watoki\curir\protocol\Url;
use watoki\curir\WebDelivery;

require_once __DIR__ . '/vendor/autoload.php';

WebDelivery::quickResponse(IndexResource::class, Admin::init(__DIR__ . '/data', Url::fromString('/admin'), WebDelivery::init()));