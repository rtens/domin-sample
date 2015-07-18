<?php
namespace rtens\blog\web;

use rtens\blog\model\Post;
use watoki\curir\Container;
use watoki\deli\Request;

class IndexResource extends Container {

    /** @var \rtens\blog\Application <- */
    protected $application;

    public function respond(Request $request) {
        return parent::respond($request);
    }

    public function doGet() {
        return [
            'posts' => $this->assemblePosts()
        ];
    }

    /**
     * @return array
     */
    private function assemblePosts() {
        return array_map(function (Post $post) {
            return [
                'title' => $post->getTitle(),
                'date' => $post->getDate()->format('j F Y'),
                'content' => $post->getText()->getContent(),
                'author' => $this->assembleAuthor($post)
            ];
        }, $this->application->getPublishedPosts());
    }

    private function assembleAuthor(Post $post) {
        $author = $this->application->getAuthor($post->getAuthor()->getId());
        return [
            'name' => $author->getName()
        ];
    }
}