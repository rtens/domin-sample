<?php
namespace rtens\blog\model\commands;

class DeletePost {

    private $id;

    /**
     * @param string $id
     */
    function __construct($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }
}