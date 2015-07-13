<?php
namespace rtens\blog\model\repositories;

use rtens\blog\model\Post;

interface PostRepository {

    /**
     * @param Post $post
     * @return null
     */
    public function create(Post $post);

    /**
     * @return Post[]
     */
    public function readAll();

    /**
     * @param string $id
     * @return Post
     */
    public function read($id);

    /**
     * @param Post $post
     * @return null
     */
    public function update(Post $post);
}