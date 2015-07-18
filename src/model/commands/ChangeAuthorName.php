<?php
namespace rtens\blog\model\commands;

class ChangeAuthorName {

    private $email;

    private $name;

    /**
     * @param \rtens\blog\model\Author-ID $email
     * @param string $name
     */
    function __construct($email, $name) {
        $this->email = $email;
        $this->name = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getName() {
        return $this->name;
    }
} 