<?php
namespace rtens\blog\model;

use rtens\domin\parameters\Html;

class Post {

    /** @var string */
    protected $id;

    /** @var string */
    protected $author;

    /** @var string */
    protected $title;

    /** @var string */
    protected $text;

    /** @var \DateTime */
    protected $date;

    /** @var null|\DateTime */
    protected $updated = null;

    /** @var boolean */
    protected $published = false;

    /** @var array|string[] */
    protected $tags = [];

    /**
     * @param string $id
     * @param string $author
     * @param string $title
     * @param Html $text
     */
    function __construct($id, $author, $title, $text) {
        $this->id = $id;
        $this->author = $author;
        $this->title = $title;
        $this->text = $text->getContent();
        $this->date = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return Html
     */
    public function getText() {
        return new Html($this->text);
    }

    /**
     * @return boolean
     */
    public function getPublished() {
        return $this->published;
    }

    /**
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * @return array|\string[]
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * @param Html $text
     */
    public function setText(Html $text) {
        $this->text = $text->getContent();
        $this->updated = new \DateTime();
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
        $this->updated = new \DateTime();
    }
} 