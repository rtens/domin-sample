<?php
namespace rtens\blog\model\commands\demo;

/**
 * This action demonstrates all *standard* fields.
 *
 * You can find the code that is Action is generated from [here](http://github.com/rtens/domin-sample/blob/master/src/model/commands/demo/DemoAction.php)
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

    /** @var \rtens\domin\parameters\Color */
    public $color;

    /** @var self::OPTION_* */
    public $enumeration;

    /** @var null|string */
    public $nullable = "default";

    /** @var \DateTime This field also accepts [natural language input](http://php.net/manual/en/datetime.formats.relative.php). Click on the calendar to activate it. */
    public $dateTime;

    /** @var \DateInterval */
    public $dateInterval;

    /** @var \rtens\domin\parameters\File */
    public $file;

    /** @var \rtens\domin\parameters\Image */
    public $image;

    /** @var \rtens\domin\parameters\Text */
    public $text;

    /** @var \rtens\domin\parameters\Html */
    public $html;

    /** @var array|string[] */
    public $array;

    /** @var inner\Foo */
    public $object;

    /** @var string|inner\Foo|inner\Bar */
    public $multi;

    /** @var \rtens\blog\model\Author-ID */
    public $identifier;

    /**
     * @param string $required Required parameters must be filled.
     */
    public function __construct($required) {
    }
}