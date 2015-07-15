<?php
namespace rtens\blog\admin;

use rtens\blog\Application;
use rtens\blog\model\Author;
use rtens\blog\model\commands\ChangeAuthorName;
use rtens\blog\model\commands\ChangeAuthorPicture;
use rtens\blog\model\commands\ChangePostTags;
use rtens\blog\model\commands\DeletePost;
use rtens\blog\model\commands\PublishPost;
use rtens\blog\model\commands\RegisterAuthor;
use rtens\blog\model\commands\UnPublishPost;
use rtens\blog\model\commands\UpdatePost;
use rtens\blog\model\commands\WritePost;
use rtens\blog\model\Post;
use rtens\blog\model\queries\ListAuthors;
use rtens\blog\model\queries\ListPosts;
use rtens\blog\model\queries\ShowPost;
use rtens\blog\storage\PersistentAuthorRepository;
use rtens\blog\storage\PersistentPostRepository;
use rtens\domin\ActionRegistry;
use rtens\domin\delivery\FieldRegistry;
use rtens\domin\delivery\RendererRegistry;
use rtens\domin\execution\RedirectResult;
use rtens\domin\web\fields\ArrayField;
use rtens\domin\web\fields\BooleanField;
use rtens\domin\web\fields\FileField;
use rtens\domin\web\fields\HtmlField;
use rtens\domin\web\fields\StringField;
use rtens\domin\web\menu\Menu;
use rtens\domin\web\menu\MenuGroup;
use rtens\domin\web\menu\MenuItem;
use rtens\domin\web\renderers\ArrayRenderer;
use rtens\domin\web\renderers\BooleanRenderer;
use rtens\domin\web\renderers\DateTimeRenderer;
use rtens\domin\web\renderers\FileRenderer;
use rtens\domin\web\renderers\HtmlRenderer;
use rtens\domin\web\renderers\object\ClassLink;
use rtens\domin\web\renderers\object\LinkRegistry;
use rtens\domin\web\renderers\object\ObjectRenderer;
use rtens\domin\web\renderers\PrimitiveRenderer;
use watoki\factory\Factory;

class Admin {

    public static function init($storageDir, $baseUrl, Factory $factory = null) {
        $pictureDir = $storageDir . '/pictures';
        $factory = $factory ?: new Factory();

        $handler = new Application(
            new PersistentAuthorRepository($storageDir),
            new PersistentPostRepository($storageDir)
        );

        $actions = $factory->setSingleton(new ActionRegistry());
        $actions->add('registerAuthor', new CommandAction(RegisterAuthor::class, $handler));
        $actions->add('listAuthors', new CommandAction(ListAuthors::class, $handler));
        $actions->add('changeAuthorPicture', new CommandAction(ChangeAuthorPicture::class, $handler));
        $actions->add('changeAuthorName', new CommandAction(ChangeAuthorName::class, $handler));
        $actions->add('writePost', (new CommandAction(WritePost::class, $handler))
            ->setAfterExecuted(function ($command, Post $post) {
                return new RedirectResult('showPost', ['id' => $post->getId()]);
            }));
        $actions->add('listPosts', new CommandAction(ListPosts::class, $handler));
        $actions->add('showPost', new CommandAction(ShowPost::class, $handler));
        $actions->add('updatePost', new CommandAction(UpdatePost::class, $handler));
        $actions->add('publishPost', new CommandAction(PublishPost::class, $handler));
        $actions->add('unpublishPost', new CommandAction(UnPublishPost::class, $handler));
        $actions->add('changeTags', new CommandAction(ChangePostTags::class, $handler));
        $actions->add('deletePost', (new CommandAction(DeletePost::class, $handler))
            ->setAfterExecuted(function () {
                return new RedirectResult('listPosts');
            }));

        $fields = $factory->setSingleton(new FieldRegistry());
        $fields->add(new StringField());
        $fields->add(new BooleanField());
        $fields->add(new FileField());
        $fields->add(new HtmlField());
        $fields->add(new ArrayField($fields));

        $authorParameters = function (Author $author) {
            return ['email' => $author->getEmail()];
        };
        $postParameters = function (Post $post) {
            return ['id' => $post->getId()];
        };
        $links = new LinkRegistry();
        $links->add(new ClassLink(Author::class, 'changeAuthorPicture', $authorParameters));
        $links->add(new ClassLink(Author::class, 'changeAuthorName', $authorParameters));
        $links->add(new ClassLink(Post::class, 'showPost', $postParameters));
        $links->add(new ClassLink(Post::class, 'changeTags', $postParameters));
        $links->add(new ClassLink(Post::class, 'updatePost', $postParameters));
        $links->add((new ClassLink(Post::class, 'publishPost', $postParameters))
            ->setHandles(function ($post) {
                return $post instanceof Post && !$post->isPublished();
            }));
        $links->add((new ClassLink(Post::class, 'unpublishPost', $postParameters))
            ->setHandles(function ($post) {
                return $post instanceof Post && $post->isPublished();
            }));
        $links->add((new ClassLink(Post::class, 'deletePost', $postParameters))
            ->setConfirmation('Are you sure?'));

        $renderers = $factory->setSingleton(new RendererRegistry());
        $renderers->add(new FileRenderer($pictureDir));
        $renderers->add(new DateTimeRenderer());
        $renderers->add(new HtmlRenderer());
        $renderers->add(new ArrayRenderer($renderers));
        $renderers->add(new ObjectRenderer($renderers, $links, $baseUrl));
        $renderers->add(new BooleanRenderer());
        $renderers->add(new PrimitiveRenderer());

        $menu = $factory->setSingleton(new Menu($actions));
        $menu->add(new MenuItem('writePost'));
        $menu->add(new MenuItem('listPosts'));
        $menu->addGroup((new MenuGroup('Authors'))
            ->add(new MenuItem('registerAuthor'))
            ->add(new MenuItem('listAuthors')));

        return $factory;
    }
}