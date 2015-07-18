<?php
namespace rtens\blog\admin;

use rtens\blog\Application;
use rtens\blog\model\Author;
use rtens\blog\model\commands\ChangeAuthorName;
use rtens\blog\model\commands\ChangeAuthorPicture;
use rtens\blog\model\commands\ChangePostTags;
use rtens\blog\model\commands\DeletePost;
use rtens\blog\model\commands\DemoAction;
use rtens\blog\model\commands\NotPublishPost;
use rtens\blog\model\commands\PublishPost;
use rtens\blog\model\commands\RegisterAuthor;
use rtens\blog\model\commands\UpdatePost;
use rtens\blog\model\commands\WritePost;
use rtens\blog\model\Post;
use rtens\blog\model\queries\ListAuthors;
use rtens\blog\model\queries\ListPosts;
use rtens\blog\model\queries\ShowAuthor;
use rtens\blog\model\queries\ShowPost;
use rtens\blog\storage\PersistentAuthorRepository;
use rtens\blog\storage\PersistentPostRepository;
use rtens\domin\ActionRegistry;
use rtens\domin\delivery\FieldRegistry;
use rtens\domin\delivery\RendererRegistry;
use rtens\domin\execution\RedirectResult;
use rtens\domin\reflection\IdentifiersProvider;
use rtens\domin\reflection\TypeFactory;
use rtens\domin\web\menu\Menu;
use rtens\domin\web\menu\MenuGroup;
use rtens\domin\web\menu\MenuItem;
use rtens\domin\web\renderers\link\ClassLink;
use rtens\domin\web\renderers\link\IdentifierLink;
use rtens\domin\web\renderers\link\LinkRegistry;
use watoki\factory\Factory;

class Admin {

    public static function initWeb($storageDir, Factory $factory = null) {
        $factory = $factory ?: new Factory();
        $identifiers = $factory->setSingleton(new IdentifiersProvider());
        $links = $factory->setSingleton(new LinkRegistry());

        (new Admin($storageDir, $factory))
            ->initActions()
            ->initLinks($links)
            ->initIdentifierProviders($identifiers)
            ->initMenu();

        return $factory;
    }

    public static function initCli($storageDir, Factory $factory = null) {
        $factory = $factory ?: new Factory();

        (new Admin($storageDir, $factory))
            ->initActions();

        return $factory;
    }

    public function __construct($storageDir, Factory $factory) {
        $this->factory = $factory;

        $this->authors = new PersistentAuthorRepository($storageDir);
        $this->posts = new PersistentPostRepository($storageDir);
        $this->handler = $factory->setSingleton(new Application($this->authors, $this->posts));

        $this->actions = $factory->setSingleton(new ActionRegistry());
        $this->fields = $factory->setSingleton(new FieldRegistry());
        $this->renderers = $factory->setSingleton(new RendererRegistry());
        $this->types = new TypeFactory();
    }

    private function initActions() {
        $this->addCommand('demo', DemoAction::class);
        /** @noinspection PhpUnusedParameterInspection */
        $this->addCommand('writePost', WritePost::class)
            ->setAfterExecuted(function ($command, Post $post) {
                return new RedirectResult('showPost', ['id' => $post->getId()]);
            });
        $this->addCommand('listPosts', ListPosts::class);
        $this->addCommand('registerAuthor', RegisterAuthor::class);
        $this->addCommand('listAuthors', ListAuthors::class);
        $this->addCommand('showPost', ShowPost::class);
        $this->addCommand('updatePost', UpdatePost::class);
        $this->addCommand('publishPost', PublishPost::class);
        $this->addCommand('unpublishPost', NotPublishPost::class);
        $this->addCommand('changeTags', ChangePostTags::class);
        $this->addCommand('deletePost', DeletePost::class)
            ->setAfterExecuted(function () {
                return new RedirectResult('listPosts');
            });
        $this->addCommand('showAuthor', ShowAuthor::class);
        $this->addCommand('changeAuthorPicture', ChangeAuthorPicture::class);
        $this->addCommand('changeAuthorName', ChangeAuthorName::class);

        return $this;
    }

    private function addCommand($id, $class) {
        $action = new CommandAction($class, $this->handler, $this->types);
        $this->actions->add($id, $action);
        return $action;
    }

    private function initLinks(LinkRegistry $links) {
        $authorParameters = function (Author $author) {
            return ['email' => $author->getEmail()];
        };
        $postParameters = function (Post $post) {
            return ['id' => $post->getId()];
        };
        $links->add(new ClassLink(Author::class, 'showAuthor', $authorParameters));
        $links->add(new ClassLink(Author::class, 'changeAuthorPicture', $authorParameters));
        $links->add(new ClassLink(Author::class, 'changeAuthorName', $authorParameters));
        $links->add(new ClassLink(Author::class, 'listPosts', function (Author $author) {
            return ['author' => $author->getEmail()];
        }));
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

        $links->add((new IdentifierLink(Author::class, 'showAuthor', 'email')));
        $links->add((new IdentifierLink(Author::class, 'listPosts', 'author')));

        return $this;
    }

    private function initMenu() {
        $menu = $this->factory->setSingleton(new Menu($this->actions));

        $menu->add(new MenuItem('writePost'));
        $menu->add(new MenuItem('listPosts'));
        $menu->addGroup((new MenuGroup('Authors'))
            ->add(new MenuItem('registerAuthor'))
            ->add(new MenuItem('listAuthors')));

        return $this;
    }

    private function initIdentifierProviders(IdentifiersProvider $identifiers) {
        $identifiers->setProvider(Author::class, function () {
            $ids = [];
            foreach ($this->authors->readAll() as $author) {
                $ids[$author->getEmail()] = $author->getName();
            }
            return $ids;
        });
        $identifiers->setProvider(Post::class, function () {
            $ids = [];
            foreach ($this->posts->readAll() as $post) {
                $ids[$post->getId()] = $post->getTitle() . ' - ' . $post->getAuthor()->getId();
            }
            return $ids;
        });

        return $this;
    }
}