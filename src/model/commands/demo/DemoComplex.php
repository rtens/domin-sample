<?php
namespace rtens\blog\model\commands\demo;

/**
 * Demonstrates how complex fields can be combined.
 *
 * The complexity (especially Images and Html in inner arrays) may
 * result in high loading times.
 *
 * See [code](http://github.com/rtens/domin-sample/blob/master/src/model/commands/demo/DemoComplex.php)
 */
class DemoComplex {

    /** @var inner\DemoBaz[] */
    public $objects;
}