<?php
namespace rtens\blog\model;

class Tag {

    /** @var string */
    private $name;

    /**
     * @param string $name
     */
    function __construct($name) {
        $this->name = $name;
    }
} 