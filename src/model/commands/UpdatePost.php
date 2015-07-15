<?php
namespace rtens\blog\model\commands;

use rtens\domin\parameters\Html;

class UpdatePost implements PostCommand {

    private $id;

    private $title;

    private $text;

    /**
     * @param string $id
     * @param string $title
     * @param Html $text
     */
    function __construct($id, $title, $text) {
        $this->id = $id;
        $this->text = $text;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return \rtens\domin\parameters\Html
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