<?php
namespace rtens\blog\model\commands\demo;

/**
 * This action demonstrates all *standard* fields.
 *
 * Complex fields can be arbitrarily combined.
 */
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

    /** @var self::OPTION_* */
    public $enumeration;

    /** @var null|string */
    public $nullable = "default";

    /** @var \DateTime This field also accepts [natural language input](http://php.net/manual/en/datetime.formats.relative.php). Click on the calendar to active it. */
    public $dateTime;

    /** @var \rtens\domin\parameters\File */
    public $file;

    /** @var \rtens\domin\parameters\Image */
    public $image;

    /** @var \rtens\domin\parameters\Html */
    public $html;

    /** @var array|string[] */
    public $array;

    /** @var inner\DemoFoo */
    public $object;

    /** @var inner\DemoBar[] Demonstrates how structured fields can be combined. */
    public $objectArray;

    /** @var string|inner\DemoFoo|inner\DemoBar */
    public $multi;

    /** @var \rtens\blog\model\Author-ID */
    public $identifier;

    /**
     * @param string $required Required parameters must be filled.
     */
    public function __construct($required) {
    }
}