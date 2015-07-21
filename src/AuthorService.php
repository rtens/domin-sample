<?php
namespace rtens\blog;

use rtens\blog\model\Author;
use rtens\blog\model\repositories\AuthorRepository;
use rtens\domin\parameters\File;
use rtens\domin\parameters\Image;

class AuthorService {

    /** @var AuthorRepository */
    private $authors;

    function __construct(AuthorRepository $authors) {
        $this->authors = $authors;
    }

    public function listAuthors() {
        return $this->authors->readAll();
    }

    /**
     * @param string $email
     * @param string $name
     * @param null|Image $picture
     * @return Author
     */
    public function registerAuthor($email, $name, Image $picture = null) {
        $author = new Author(
            $email,
            $name,
            $picture->getFile());
        $this->authors->create($author);
        return $author;
    }

    /**
     * @param \rtens\blog\model\Author-ID $email
     * @param string $name
     */
    public function changeAuthorName($email, $name) {
        $author = $this->authors->read($email);
        $author->setName($name);
        $this->authors->update($author);
    }

    /**
     * @param Author-ID $email
     * @param Image $picture
     */
    public function changeAuthorPicture($email, Image $picture) {
        $author = $this->authors->read($email);
        $author->setPicture($picture->getFile());
        $this->authors->update($author);
    }

    /**
     * @param Author-ID $email
     * @return Author
     */
    public function showAuthor($email) {
        return $this->authors->read($email);
    }
}