<?php
namespace rtens\blog;

use rtens\blog\model\Author;
use rtens\blog\model\commands\ChangeAuthorName;
use rtens\blog\model\commands\ChangeAuthorPicture;
use rtens\blog\model\commands\RegisterAuthor;
use rtens\blog\model\commands\UpdatePost;
use rtens\blog\model\commands\WritePost;
use rtens\blog\model\Post;
use rtens\blog\model\repositories\AuthorRepository;
use rtens\blog\model\repositories\PostRepository;

class Application {

    /** @var AuthorRepository */
    private $authors;

    /** @var PostRepository */
    private $posts;

    function __construct(AuthorRepository $authors, PostRepository $posts) {
        $this->authors = $authors;
        $this->posts = $posts;
    }

    public function handleListAuthors() {
        return $this->authors->readAll();
    }

    public function handleRegisterAuthor(RegisterAuthor $command) {
        $this->authors->create(new Author(
            $command->getEmail(),
            $command->getName(),
            $command->getPicture()));
    }

    public function handleChangeAuthorName(ChangeAuthorName $command) {
        $author = $this->authors->read($command->getEmail());
        $author->setName($command->getName());
        $this->authors->update($author);
    }

    public function fillChangeAuthorName($parameters) {
        if (array_key_exists('email', $parameters)) {
            $author = $this->authors->read($parameters['email']);
            $parameters['name'] = $author->getName();
        }
        return $parameters;
    }

    public function handleChangeAuthorPicture(ChangeAuthorPicture $command) {
        $author = $this->authors->read($command->getEmail());
        $author->setPicture($command->getPicture());
        $this->authors->update($author);
    }

    public function handleWritePost(WritePost $command) {
        $author = $this->authors->read($command->getAuthor());
        $this->posts->create(new Post(
            time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $command->getTitle()),
            $author->getEmail(),
            $command->getTitle(),
            $command->getText()));
    }

    public function handleListPosts() {
        return $this->posts->readAll();
    }

    public function handleUpdatePost(UpdatePost $command) {
        $post = $this->posts->read($command->getId());
        $post->setTitle($command->getTitle());
        $post->setText($command->getText());
        $this->posts->update($post);
    }

    public function fillUpdatePost($parameters) {
        if (isset($parameters['id'])) {
            $post = $this->posts->read($parameters['id']);
            $parameters['title'] = $post->getTitle();
            $parameters['text'] = $post->getText();
        }
        return $parameters;
    }

} 