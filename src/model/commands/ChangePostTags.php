<?php
namespace rtens\blog\model\commands;

class ChangePostTags {

    private $id;

    private $tags;

    /**
     * @param string $id
     * @param array|string[] $tags
     */
    function __construct($id, $tags) {
        $this->id = $id;
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return array|string[]
     */
    public function getTags() {
        return $this->tags;
    }
} 