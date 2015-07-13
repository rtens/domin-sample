<?php
namespace rtens\blog\model\commands;

class ChangeAuthorName {

    private $email;

    private $name;

    /**
     * @param string $email
     * @param string $name
     */
    function __construct($email, $name) {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
} 