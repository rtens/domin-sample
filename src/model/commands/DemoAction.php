<?php
namespace rtens\blog\model\commands;

class DemoAction {

    const OPTION_ONE = 'one';
    const OPTION_TWO = 'two';
    const OPTION_THREE = 'three';

    /** @var string */
    public $string;

    /** @var integer */
    public $integer;

    /** @var boolean */
    public $boolean = true;

    /** @var self::OPTION_ */
    public $enumeration;

    /** @var null|string */
    public $nullable = "default";

    /** @var \DateTime */
    public $dateTime;

    /** @var \rtens\domin\parameters\File */
    public $file;

    /** @var \rtens\domin\parameters\Html */
    public $html;

    /** @var array|string[] */
    public $array;

    /** @var DemoFoo */
    public $object;

    /** @var DemoBar[] */
    public $objectArray;

    /** @var string|DemoFoo|DemoBar */
    public $multi;

    /** @var \rtens\blog\model\Author-ID */
    public $identifier;

    /**
     * @param string $required
     */
    public function __construct($required) {

    }
}

class DemoFoo {

    /** @var string */
    public $foo;

    /** @var null|DemoBar */
    public $bar;
}

class DemoBar {

    /** @var string */
    public $foo;

    /** @var string */
    public $bar;
}