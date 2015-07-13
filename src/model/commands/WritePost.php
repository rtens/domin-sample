<?php
namespace rtens\blog\model\commands;

use rtens\domin\parameters\Html;

class WritePost {

    private $author;

    private $title;

    private $text;

    private $tags;

    /**
     * @param string $author
     * @param string $title
     * @param Html $text
     */
    function __construct($author, $title, $text) {
        $this->author = $author;
        $this->tags = [];
        $this->text = $text;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * @return array|\string[]
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * @return Html
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }
} 