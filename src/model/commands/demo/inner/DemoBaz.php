<?php
namespace rtens\blog\model\commands\demo\inner;

use rtens\domin\parameters\File;
use rtens\domin\parameters\Html;
use rtens\domin\parameters\Image;

class DemoBaz {

    /** @var null|DemoBar[] */
    public $bars;

    /** @var Image[]|File[]|Html[] */
    public $stuff;
}