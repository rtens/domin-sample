<?php
namespace rtens\blog\model;

use rtens\domin\parameters\File;
use rtens\domin\parameters\MemoryFile;

class Author {

    /** @var string */
    protected $email;

    /** @var string */
    protected $name;

    /** @var array|string[] */
    protected $picture;

    /**
     * @param string $email
     * @param string $name
     * @param null|File $picture
     */
    function __construct($email, $name, $picture = null) {
        $this->email = $email;
        $this->name = $name;
        $this->setPicture($picture);
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
     * @return File
     */
    public function getPicture() {
        return new MemoryFile(
            $this->picture['name'],
            $this->picture['type'],
            base64_decode($this->picture['data'])
        );
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @param File $picture
     */
    public function setPicture(File $picture) {
        $this->picture = [
            'name' => $picture->getName(),
            'type' => $picture->getType(),
            'data' => base64_encode($picture->getContent())
        ];
    }

}