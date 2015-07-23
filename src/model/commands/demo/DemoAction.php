<?php
namespace rtens\blog\model\commands\demo;

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

    /** @var \DateTime */
    public $dateTime;

    /** @var \rtens\domin\parameters\File */
    public $file;

    /** @var \rtens\domin\parameters\Image */
    private $image;

    /** @var \rtens\domin\parameters\Html */
    public $html;

    /** @var array|string[] */
    public $array;

    /** @var inner\DemoFoo */
    public $object;

    /** @var inner\DemoBar[] */
    public $objectArray;

    /** @var string|inner\DemoFoo|inner\DemoBar */
    public $multi;

    /** @var \rtens\blog\model\Author-ID */
    public $identifier;

    /**
     * @param string $required
     */
    public function __construct($required) {
    }

    /**
     * @return \rtens\domin\parameters\Image
     */
    public function getImage() {
        return $this->image;
    }

    public function setImage(\rtens\domin\parameters\Image $image = null) {
        $this->image = $image;
    }
}