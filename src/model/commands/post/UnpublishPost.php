<?php
namespace rtens\blog\model\commands\post;

class UnpublishPost implements \rtens\blog\model\commands\PostCommand {

    private $id;

    /**
     * @param \rtens\blog\model\Post-ID $id
     */
    function __construct($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }
}