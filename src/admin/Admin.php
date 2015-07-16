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
use rtens\domin\web\fields\DateTimeField;
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
use watoki\reflect\TypeFactory;

class Admin {

    private $menu;

    public static function init($storageDir, $baseUrl, Factory $factory = null) {
        $factory = $factory ?: new Factory();
        (new Admin($storageDir, $baseUrl, $factory))->initialize();
        return $factory;
    }

    public function __construct($storageDir, $baseUrl, Factory $factory) {
        $this->pictureDir = $storageDir . '/pictures';
        $this->baseUrl = $baseUrl;

        $this->handler = new Application(
            new PersistentAuthorRepository($storageDir),
            new PersistentPostRepository($storageDir)
        );

        $this->actions = $factory->setSingleton(new ActionRegistry());
        $this->fields = $factory->setSingleton(new FieldRegistry());
        $this->renderers = $factory->setSingleton(new RendererRegistry());
        $this->menu = $factory->setSingleton(new Menu($this->actions));
        $this->links = new LinkRegistry();
        $this->types = new TypeFactory();
    }

    public function initialize() {
        $this->initActions();
        $this->initFields();
        $this->initLinks();
        $this->initRenderers();
        $this->initMenu();
    }

    private function initActions() {
        $this->addCommand('registerAuthor', RegisterAuthor::class);
        $this->addCommand('listAuthors', ListAuthors::class);
        $this->addCommand('changeAuthorPicture', ChangeAuthorPicture::class);
        $this->addCommand('changeAuthorName', ChangeAuthorName::class);
        $this->addCommand('writePost', WritePost::class)
            ->setAfterExecuted(function ($command, Post $post) {
                return new RedirectResult('showPost', ['id' => $post->getId()]);
            });
        $this->addCommand('listPosts', ListPosts::class);
        $this->addCommand('showPost', ShowPost::class);
        $this->addCommand('updatePost', UpdatePost::class);
        $this->addCommand('publishPost', PublishPost::class);
        $this->addCommand('unpublishPost', UnPublishPost::class);
        $this->addCommand('changeTags', ChangePostTags::class);
        $this->addCommand('deletePost', DeletePost::class)
            ->setAfterExecuted(function () {
                return new RedirectResult('listPosts');
            });
    }

    private function addCommand($id, $class) {
        $action = new CommandAction($class, $this->handler, $this->types);
        $this->actions->add($id, $action);
        return $action;
    }

    private function initLinks() {
        $authorParameters = function (Author $author) {
            return ['email' => $author->getEmail()];
        };
        $postParameters = function (Post $post) {
            return ['id' => $post->getId()];
        };
        $this->links->add(new ClassLink(Author::class, 'changeAuthorPicture', $authorParameters));
        $this->links->add(new ClassLink(Author::class, 'changeAuthorName', $authorParameters));
        $this->links->add(new ClassLink(Post::class, 'showPost', $postParameters));
        $this->links->add(new ClassLink(Post::class, 'changeTags', $postParameters));
        $this->links->add(new ClassLink(Post::class, 'updatePost', $postParameters));
        $this->links->add((new ClassLink(Post::class, 'publishPost', $postParameters))
            ->setHandles(function ($post) {
                return $post instanceof Post && !$post->isPublished();
            }));
        $this->links->add((new ClassLink(Post::class, 'unpublishPost', $postParameters))
            ->setHandles(function ($post) {
                return $post instanceof Post && $post->isPublished();
            }));
        $this->links->add((new ClassLink(Post::class, 'deletePost', $postParameters))
            ->setConfirmation('Are you sure?'));
    }

    private function initFields() {
        $this->fields->add(new StringField());
        $this->fields->add(new BooleanField());
        $this->fields->add(new FileField());
        $this->fields->add(new HtmlField());
        $this->fields->add(new ArrayField($this->fields));
        $this->fields->add(new DateTimeField());
    }

    private function initRenderers() {
        $this->renderers->add(new FileRenderer($this->pictureDir));
        $this->renderers->add(new DateTimeRenderer());
        $this->renderers->add(new HtmlRenderer());
        $this->renderers->add(new ArrayRenderer($this->renderers));
        $this->renderers->add(new ObjectRenderer($this->renderers, $this->links, $this->baseUrl, $this->types));
        $this->renderers->add(new BooleanRenderer());
        $this->renderers->add(new PrimitiveRenderer());
    }

    private function initMenu() {
        $this->menu->add(new MenuItem('writePost'));
        $this->menu->add(new MenuItem('listPosts'));
        $this->menu->addGroup((new MenuGroup('Authors'))
            ->add(new MenuItem('registerAuthor'))
            ->add(new MenuItem('listAuthors')));
    }
}