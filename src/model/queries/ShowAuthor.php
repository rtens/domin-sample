<?php
namespace rtens\blog\model\queries;

class ShowAuthor {

    private $email;

    /**
     * @param \rtens\blog\model\Author-ID $email
     */
    function __construct($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }
}