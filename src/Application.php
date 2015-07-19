<?php
namespace rtens\blog;

use rtens\blog\model\Author;
use rtens\blog\model\commands\ChangeAuthorName;
use rtens\blog\model\commands\ChangeAuthorPicture;
use rtens\blog\model\commands\ChangePostTags;
use rtens\blog\model\commands\DeletePost;
use rtens\blog\model\commands\demo\DemoAction;
use rtens\blog\model\commands\PublishPost;
use rtens\blog\model\commands\RegisterAuthor;
use rtens\blog\model\commands\UnpublishPost;
use rtens\blog\model\commands\UpdatePost;
use rtens\blog\model\commands\WritePost;
use rtens\blog\model\Post;
use rtens\blog\model\queries\ListPosts;
use rtens\blog\model\queries\ShowAuthor;
use rtens\blog\model\queries\ShowPost;
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

    public function handleDemoAction(DemoAction $demo) {
        return $demo;
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

    public function handleChangeAuthorPicture(ChangeAuthorPicture $command) {
        $author = $this->authors->read($command->getEmail());
        $author->setPicture($command->getPicture());
        $this->authors->update($author);
    }

    public function handleDeletePost(DeletePost $command) {
        $this->posts->delete($command->getId());
    }

    public function handleWritePost(WritePost $command) {
        $author = $this->authors->read($command->getAuthor());
        $post = new Post(
            time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $command->getTitle()),
            $author->getEmail(),
            $command->getTitle(),
            $command->getText());
        $post->setPublished($command->getPublished());
        $post->setTags($command->getTags());
        $this->posts->create($post);

        return $post;
    }

    public function handleListPosts(ListPosts $command) {
        return array_filter($this->posts->readAll(), function (Post $post) use ($command) {
            return !$command->getAuthor() || $post->getAuthor()->getId() == $command->getAuthor();
        });
    }

    public function handleShowPost(ShowPost $command) {
        return $this->posts->read($command->getId());
    }

    public function handleShowAuthor(ShowAuthor $command) {
        return $this->authors->read($command->getEmail());
    }

    public function handleUpdatePost(UpdatePost $command) {
        $post = $this->posts->read($command->getId());
        $post->setTitle($command->getTitle());
        $post->setText($command->getText());
        $this->posts->update($post);
    }

    public function handlePublishPost(PublishPost $command) {
        $post = $this->posts->read($command->getId());
        $post->setPublished($command->getPublish());
        $this->posts->update($post);
    }

    public function handleUnpublishPost(UnpublishPost $command) {
        $post = $this->posts->read($command->getId());
        $post->setPublished(null);
        $this->posts->update($post);
    }

    public function handleChangePostTags(ChangePostTags $command) {
        $post = $this->posts->read($command->getId());
        $post->setTags($command->getTags());
        $this->posts->update($post);
    }

    public function getAuthor($email) {
        return $this->authors->read($email);
    }

    public function getPublishedPosts() {
        $published = array_filter($this->posts->readAll(), function (Post $post) {
            return $post->isPublished();
        });
        usort($published, function (Post $a, Post $b) {
            return $a->getDate() < $b->getDate() ? 1 : -1;
        });
        return $published;
    }
} 