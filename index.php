<?php

use rtens\blog\Admin;
use rtens\blog\web\IndexResource;
use watoki\curir\WebDelivery;

require_once __DIR__ . '/vendor/autoload.php';

WebDelivery::quickResponse(IndexResource::class, Admin::initWeb(__DIR__ . '/data', WebDelivery::init()));