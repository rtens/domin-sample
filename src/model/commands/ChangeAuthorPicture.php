<?php
namespace rtens\blog\model\commands;

use rtens\domin\parameters\File;

class ChangeAuthorPicture {

    private $email;

    private $picture;

    /**
     * @param string $email
     * @param File $picture
     */
    function __construct($email, $picture) {
        $this->email = $email;
        $this->picture = $picture;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return File
     */
    public function getPicture() {
        return $this->picture;
    }
} 