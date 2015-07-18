<?php
namespace rtens\blog\model\queries;

class ListPosts {


    private $author;

    /**
     * @param null|\rtens\blog\model\Author-ID $email
     */
    function __construct($author = null) {
        $this->author = $author;
    }

    /**
     * @return null|\rtens\blog\model\Author-ID
     */
    public function getAuthor() {
        return $this->author;
    }
}