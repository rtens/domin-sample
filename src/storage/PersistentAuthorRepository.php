<?php
namespace rtens\blog\storage;

use rtens\blog\model\Author;
use rtens\blog\model\repositories\AuthorRepository;
use watoki\stores\file\FileStore;

class PersistentAuthorRepository implements AuthorRepository {

    /** @var \watoki\stores\file\FileStore */
    private $store;

    function __construct($rootDir) {
        $this->store = FileStore::forClass(Author::class, $rootDir . '/authors');
    }

    public function create(Author $author) {
        $key = $author->getEmail();

        if ($this->store->exists($key)) {
            throw new \Exception("Author with email [{$author->getEmail()}] already exists.");
        }
        $this->store->create($author, $key);
    }

    /**
     * @param Author $author
     * @return null
     */
    public function update(Author $author) {
        $this->store->update($author);
    }

    /**
     * @return Author[]
     */
    public function readAll() {
        return array_map(function ($key) {
            return $this->store->read($key);
        }, $this->store->keys());
    }

    /**
     * @param string $email
     * @return Author
     */
    public function read($email) {
        return $this->store->read($email);
    }
}