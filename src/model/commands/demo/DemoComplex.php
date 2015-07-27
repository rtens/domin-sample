<?php
namespace rtens\blog\model\commands\demo;

/**
 * Demonstrates how complex fields can be combined.
 *
 * The complexity (especially Images and Html in inner arrays) may
 * result in higher loading times.
 *
 * You can find the code that this action is generated from
 * [here](http://github.com/rtens/domin-sample/blob/master/src/model/commands/demo/DemoComplex.php)
 * and [here](http://github.com/rtens/domin-sample/blob/master/src/model/commands/demo/inner/DemoBaz.php).
 */
class DemoComplex {

    /** @var inner\DemoBaz[] */
    public $objects;
}