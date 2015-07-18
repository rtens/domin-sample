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
use rtens\domin\cli\renderers\ArrayRenderer as CliArrayRenderer;
use rtens\domin\cli\renderers\BooleanRenderer as CliBooleanRenderer;
use rtens\domin\cli\renderers\DateTimeRenderer as CliDateTimeRenderer;
use rtens\domin\cli\renderers\FileRenderer as CliFileRenderer;
use rtens\domin\cli\renderers\HtmlRenderer as CliHtmlRenderer;
use rtens\domin\cli\renderers\IdentifierRenderer as CliIdentifierRenderer;
use rtens\domin\cli\renderers\ObjectRenderer as CliObjectRenderer;
use rtens\domin\cli\renderers\PrimitiveRenderer as CliPrimitiveRenderer;
use rtens\domin\delivery\FieldRegistry;
use rtens\domin\delivery\RendererRegistry;
use rtens\domin\execution\RedirectResult;
use rtens\domin\reflection\IdentifiersProvider;
use rtens\domin\reflection\TypeFactory;
use rtens\domin\web\fields\ArrayField;
use rtens\domin\web\fields\BooleanField;
use rtens\domin\web\fields\DateTimeField;
use rtens\domin\web\fields\EnumerationField;
use rtens\domin\web\fields\FileField;
use rtens\domin\web\fields\HtmlField;
use rtens\domin\web\fields\IdentifierField;
use rtens\domin\web\fields\MultiField;
use rtens\domin\web\fields\NullableField;
use rtens\domin\web\fields\ObjectField;
use rtens\domin\web\fields\PrimitiveField;
use rtens\domin\cli\fields\ArrayField as CliArrayField;
use rtens\domin\cli\fields\BooleanField as CliBooleanField;
use rtens\domin\cli\fields\DateTimeField as CliDateTimeField;
use rtens\domin\cli\fields\EnumerationField as CliEnumerationField;
use rtens\domin\cli\fields\FileField as CliFileField;
use rtens\domin\cli\fields\HtmlField as CliHtmlField;
use rtens\domin\cli\fields\IdentifierField as CliIdentifierField;
use rtens\domin\cli\fields\MultiField as CliMultiField;
use rtens\domin\cli\fields\NullableField as CliNullableField;
use rtens\domin\cli\fields\ObjectField as CliObjectField;
use rtens\domin\cli\fields\PrimitiveField as CliPrimitiveField;
use rtens\domin\web\menu\Menu;
use rtens\domin\web\menu\MenuGroup;
use rtens\domin\web\menu\MenuItem;
use rtens\domin\web\renderers\ArrayRenderer;
use rtens\domin\web\renderers\BooleanRenderer;
use rtens\domin\web\renderers\DateTimeRenderer;
use rtens\domin\web\renderers\FileRenderer;
use rtens\domin\web\renderers\HtmlRenderer;
use rtens\domin\web\renderers\IdentifierRenderer;
use rtens\domin\web\renderers\link\ClassLink;
use rtens\domin\web\renderers\link\IdentifierLink;
use rtens\domin\web\renderers\link\LinkPrinter;
use rtens\domin\web\renderers\link\LinkRegistry;
use rtens\domin\web\renderers\ObjectRenderer;
use rtens\domin\web\renderers\PrimitiveRenderer;
use watoki\factory\Factory;

class Admin {

    public static function initWeb($storageDir, $baseUrl, Factory $factory = null) {
        $factory = $factory ?: new Factory();
        $links = new LinkRegistry();
        $identifiers = new IdentifiersProvider();

        (new Admin($storageDir, $factory))
            ->initActions()
            ->initWebFields($identifiers)
            ->initWebRenderers($baseUrl, $links)
            ->initLinks($links)
            ->initIdentifierProviders($identifiers)
            ->initMenu();

        return $factory;
    }

    public static function initCli($storageDir, callable $read, Factory $factory = null) {
        $factory = $factory ?: new Factory();

        (new Admin($storageDir, $factory))
            ->initActions()
            ->initCliFields($read)
            ->initCliRenderers();

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

    private function initWebFields(IdentifiersProvider $identifiers) {
        $this->fields->add(new PrimitiveField());
        $this->fields->add(new BooleanField());
        $this->fields->add(new FileField());
        $this->fields->add(new HtmlField());
        $this->fields->add(new DateTimeField());
        $this->fields->add(new ArrayField($this->fields));
        $this->fields->add(new NullableField($this->fields));
        $this->fields->add(new ObjectField($this->types, $this->fields));
        $this->fields->add(new MultiField($this->fields));
        $this->fields->add(new IdentifierField($this->fields, $identifiers));
        $this->fields->add(new EnumerationField($this->fields));

        return $this;
    }

    private function initWebRenderers($baseUrl, LinkRegistry $links) {
        $links = new LinkPrinter($baseUrl, $links);

        $this->renderers->add(new BooleanRenderer());
        $this->renderers->add(new PrimitiveRenderer());
        $this->renderers->add(new DateTimeRenderer());
        $this->renderers->add(new HtmlRenderer());
        $this->renderers->add(new IdentifierRenderer($links));
        $this->renderers->add(new FileRenderer());
        $this->renderers->add(new ArrayRenderer($this->renderers));
        $this->renderers->add(new ObjectRenderer($this->renderers, $this->types, $links));

        return $this;
    }

    private function initCliFields(callable $read) {
        $this->fields->add(new CliPrimitiveField());
        $this->fields->add(new CliBooleanField());
        $this->fields->add(new CliFileField());
        $this->fields->add(new CliHtmlField($read));
        $this->fields->add(new CliDateTimeField());
        $this->fields->add(new CliArrayField($this->fields, $read));
        $this->fields->add(new CliNullableField($this->fields, $read));
        $this->fields->add(new CliObjectField($this->types, $this->fields, $read));
        $this->fields->add(new CliMultiField($this->fields, $read));
        $this->fields->add(new CliIdentifierField($this->fields));
        $this->fields->add(new CliEnumerationField($this->fields));

        return $this;
    }

    private function initCliRenderers() {
        $this->renderers->add(new CliBooleanRenderer());
        $this->renderers->add(new CliPrimitiveRenderer());
        $this->renderers->add(new CliDateTimeRenderer());
        $this->renderers->add(new CliHtmlRenderer());
        $this->renderers->add(new CliIdentifierRenderer());
        $this->renderers->add(new CliFileRenderer(''));
        $this->renderers->add(new CliArrayRenderer($this->renderers));
        $this->renderers->add(new CliObjectRenderer($this->renderers, $this->types));
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