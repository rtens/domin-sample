<?php
namespace rtens\blog;

use rtens\blog\model\Author;
use rtens\blog\model\repositories\AuthorRepository;
use rtens\domin\parameters\File;
use rtens\domin\parameters\Image;

class Authors {

    /** @var AuthorRepository */
    private $authors;

    function __construct(AuthorRepository $authors) {
        $this->authors = $authors;
    }

    public function all() {
        return $this->authors->readAll();
    }

    /**
     * Registers a new Author who can write blog posts.
     *
     * This is really just a second line of description.
     *
     * @param string $email The email address wont be visible in the blog.
     * @param string $name
     * @param null|Image $picture
     * @return Author
     */
    public function register($email, $name, Image $picture = null) {
        $author = new Author(
            $email,
            $name,
            $picture ? $picture->getFile() : null);
        $this->authors->create($author);
        return $author;
    }

    /**
     * @param \rtens\blog\model\Author-ID $email
     * @param string $name
     */
    public function changeName($email, $name) {
        $author = $this->authors->read($email);
        $author->setName($name);
        $this->authors->update($author);
    }

    /**
     * @param Author-ID $email
     * @param Image $picture
     */
    public function changePicture($email, Image $picture) {
        $author = $this->authors->read($email);
        $author->setPicture($picture->getFile());
        $this->authors->update($author);
    }

    /**
     * @param Author-ID $email
     * @return Author
     */
    public function show($email) {
        return $this->authors->read($email);
    }
}