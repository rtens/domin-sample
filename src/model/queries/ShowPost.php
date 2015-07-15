<?php
namespace rtens\blog\model\queries;

class ShowPost {

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