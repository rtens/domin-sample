<?php
namespace rtens\blog\model\commands\demo\inner;

use rtens\domin\parameters\File;
use rtens\domin\parameters\Html;
use rtens\domin\parameters\Image;

class Baz {

    /** @var null|Foo[] */
    public $foos;

    /** @var Image[]|File[]|Html[] */
    public $stuff;
}