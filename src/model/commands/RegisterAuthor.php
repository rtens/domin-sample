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
     * @param \rtens\domin\parameters\File $picture
     */
    function __construct($email, $name, File $picture = null) {
        $this->email = $email;
        $this->name = $name;
        $this->picture = $picture;
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

    /**
     * @return \rtens\domin\parameters\File
     */
    public function getPicture() {
        return $this->picture;
    }
}