<?php
namespace rtens\blog\model\commands\post;

use rtens\domin\parameters\Html;

class UpdatePost implements \rtens\blog\model\commands\PostCommand {

    private $id;

    private $title;

    private $text;

    /**
     * @param \rtens\blog\model\Post-ID $id
     * @param string $title
     * @param Html $text
     */
    function __construct($id, $title, $text) {
        $this->id = $id;
        $this->text = $text;
        $this->title = $title;
    }

    public function getId() {
        return $this->id;
    }

    public function getText() {
        return $this->text;
    }

    public function getTitle() {
        return $this->title;
    }
} 