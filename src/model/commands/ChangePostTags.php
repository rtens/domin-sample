<?php
namespace rtens\blog\model\commands;

class ChangePostTags {

    private $id;

    private $tags;

    /**
     * @param \rtens\blog\model\Post-ID $id
     * @param array|string[] $tags
     */
    function __construct($id, $tags) {
        $this->id = $id;
        $this->tags = $tags;
    }

    public function getId() {
        return $this->id;
    }

    public function getTags() {
        return $this->tags;
    }
} 