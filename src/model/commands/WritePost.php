<?php
namespace rtens\blog\model\commands;

use rtens\domin\parameters\Html;

class WritePost {

    private $author;

    private $title;

    private $text;

    private $tags;

    private $published;

    /**
     * @param string $author
     * @param string $title
     * @param Html $text
     * @param array|string[] $tags
     * @param bool $published
     */
    function __construct($author, $title, $text, $tags = [], $published = true) {
        $this->author = $author;
        $this->tags = $tags;
        $this->text = $text;
        $this->title = $title;
        $this->published = $published;
    }

    /**
     * @return string
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * @return array|string[]
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

    /**
     * @return boolean
     */
    public function isPublished() {
        return $this->published;
    }
} 