<?php
namespace rtens\blog\model\commands\post;

use rtens\blog\model\Author;
use rtens\domin\parameters\Html;

/**
 * Write and publish a new blog post.
 */
class WritePost {

    private $author;

    private $title;

    private $text;

    private $tags;

    private $published;

    /**
     * @param Author-ID $author
     * @param string $title
     * @param Html $text
     * @param array|string[] $tags
     * @param null|\DateTime $published
     */
    function __construct($author, $title, $text, $tags = [], \DateTime $published = null) {
        $this->author = $author;
        $this->tags = $tags;
        $this->text = $text;
        $this->title = $title;
        $this->published = $published;
    }

    /**
     * @return Author-ID
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
     * @return \DateTime|null
     */
    public function getPublished() {
        return $this->published;
    }
} 