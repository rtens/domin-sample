<?php
namespace rtens\blog\model\commands;

use rtens\domin\parameters\File;

class ChangeAuthorPicture {

    private $email;

    private $picture;

    /**
     * @param \rtens\blog\model\Author-ID $email
     * @param File $picture
     */
    function __construct($email, $picture) {
        $this->email = $email;
        $this->picture = $picture;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPicture() {
        return $this->picture;
    }
} 