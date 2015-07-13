<?php
namespace rtens\blog\model\repositories;

use rtens\blog\model\Author;

interface AuthorRepository {

    /**
     * @param Author $author
     * @return null
     */
    public function create(Author $author);

    /**
     * @param Author $author
     * @return null
     */
    public function update(Author $author);

    /**
     * @return Author[]
     */
    public function readAll();

    /**
     * @param string $email
     * @return Author
     */
    public function read($email);
} 