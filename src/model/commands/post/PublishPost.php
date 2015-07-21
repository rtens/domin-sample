<?php
namespace rtens\blog\model\commands\post;

class PublishPost implements \rtens\blog\model\commands\PostCommand {

    private $id;
    private $publish;

    /**
     * @param \rtens\blog\model\Post-ID $id
     * @param \DateTime $publish
     */
    function __construct($id, \DateTime $publish) {
        $this->id = $id;
        $this->publish = $publish;
    }

    public function getId() {
        return $this->id;
    }

    public function getPublish() {
        return $this->publish;
    }
}