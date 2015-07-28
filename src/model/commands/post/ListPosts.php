<?php
namespace rtens\blog\model\commands\post;

class ListPosts {

    private $author;

    private $published;

    /**
     * @param null|\rtens\blog\model\Author-ID $author
     * @param null|bool $published
     */
    function __construct($author = null, $published = null) {
        $this->author = $author;
        $this->published = $published;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getPublished() {
        return $this->published;
    }
}