<?php
namespace rtens\blog;

use rtens\blog\model\Author;
use rtens\blog\model\commands\ChangeAuthorName;
use rtens\blog\model\commands\ChangeAuthorPicture;
use rtens\blog\model\commands\PublishPost;
use rtens\blog\model\commands\RegisterAuthor;
use rtens\blog\model\commands\UnPublishPost;
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
        if ($parameters['email']) {
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
        $post = new Post(
            time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $command->getTitle()),
            $author->getEmail(),
            $command->getTitle(),
            $command->getText());
        $post->setPublished($command->isPublished());
        $this->posts->create($post);
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
        if ($parameters['id']) {
            $post = $this->posts->read($parameters['id']);
            $parameters['title'] = $post->getTitle();
            $parameters['text'] = $post->getText();
        }
        return $parameters;
    }

    public function handlePublishPost(PublishPost $command) {
        $post = $this->posts->read($command->getId());
        $post->setPublished(true);
        $this->posts->update($post);
    }

    public function handleUnPublishPost(UnPublishPost $command) {
        $post = $this->posts->read($command->getId());
        $post->setPublished(false);
        $this->posts->update($post);
    }

} 