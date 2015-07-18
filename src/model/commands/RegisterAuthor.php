<?php
namespace rtens\blog\model\commands;

use rtens\domin\parameters\File;

class RegisterAuthor {

    private $email;

    private $name;

    private $picture;

    /**
     * @param string $email
     * @param string $name
     * @param null|\rtens\domin\parameters\File $picture
     */
    function __construct($email, $name, File $picture = null) {
        $this->email = $email;
        $this->name = $name;
        $this->picture = $picture;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getName() {
        return $this->name;
    }

    public function getPicture() {
        return $this->picture;
    }
}