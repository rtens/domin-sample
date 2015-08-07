<?php
namespace rtens\blog;

use rtens\blog\model\commands\post\ChangePostTags;
use rtens\blog\model\commands\post\DeletePost;
use rtens\blog\model\commands\post\ListPosts;
use rtens\blog\model\commands\post\PublishPost;
use rtens\blog\model\commands\post\ShowPost;
use rtens\blog\model\commands\post\UnpublishPost;
use rtens\blog\model\commands\post\UpdatePost;
use rtens\blog\model\commands\post\WritePost;
use rtens\blog\model\Post;
use rtens\blog\model\repositories\AuthorRepository;
use rtens\blog\model\repositories\PostRepository;

class Posts {

    /** @var AuthorRepository */
    private $authors;

    /** @var PostRepository */
    private $posts;

    function __construct(AuthorRepository $authors, PostRepository $posts) {
        $this->authors = $authors;
        $this->posts = $posts;
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
        return array_values(array_filter($this->posts->readAll(), function (Post $post) use ($command) {
            return (is_null($command->getAuthor()) || $command->getAuthor() == $post->getAuthor()->getId())
                && (is_null($command->getPublished()) || $command->getPublished() === $post->isPublished());
        }));
    }

    public function handleShowPost(ShowPost $command) {
        return $this->posts->read($command->getId());
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