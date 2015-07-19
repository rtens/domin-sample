<?php
namespace rtens\blog;

use rtens\blog\model\Author;
use rtens\blog\model\repositories\AuthorRepository;
use rtens\domin\parameters\File;

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
     * @param null|\rtens\domin\parameters\File $picture
     * @return Author
     */
    public function registerAuthor($email, $name, File $picture = null) {
        $author = new Author(
            $email,
            $name,
            $picture);
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
     * @param File $picture
     */
    public function changeAuthorPicture($email, File $picture) {
        $author = $this->authors->read($email);
        $author->setPicture($picture);
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