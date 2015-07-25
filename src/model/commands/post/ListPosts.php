<?php
namespace rtens\blog\model\commands\post;

class ListPosts {

    private $author;

    /**
     * @param null|\rtens\blog\model\Author-ID $author
     */
    function __construct($author = null) {
        $this->author = $author;
    }

    public function getAuthor() {
        return $this->author;
    }
}