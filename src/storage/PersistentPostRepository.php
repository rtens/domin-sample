<?php
namespace rtens\blog\storage;

use rtens\blog\model\Post;
use rtens\blog\model\repositories\PostRepository;
use watoki\stores\file\FileStore;

class PersistentPostRepository implements PostRepository {

    /** @var \watoki\stores\file\FileStore */
    private $store;

    function __construct($rootDir) {
        $this->store = FileStore::forClass(Post::class, $rootDir . '/posts');
    }

    public function create(Post $post) {
        $this->store->create($post, $post->getId());
    }

    /**
     * @return array|Post[]
     */
    public function readAll() {
        return array_map(function ($key) {
            return $this->store->read($key);
        }, $this->store->keys());
    }

    public function read($id) {
        return $this->store->read($id);
    }

    /**
     * @param Post $post
     * @return null
     */
    public function update(Post $post) {
        $this->store->update($post);
    }

    /**
     * @param string $id
     * @return null
     */
    public function delete($id) {
        $this->store->delete($id);
    }
}