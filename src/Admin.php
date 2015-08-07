<?php
namespace rtens\blog;

use rtens\blog\model\Author;
use rtens\blog\model\commands\demo\inner\Bar;
use rtens\blog\model\commands\post\ChangePostTags;
use rtens\blog\model\commands\post\DeletePost;
use rtens\blog\model\commands\post\ListPosts;
use rtens\blog\model\commands\post\UpdatePost;
use rtens\blog\model\commands\post\WritePost;
use rtens\blog\model\Post;
use rtens\blog\storage\PersistentAuthorRepository;
use rtens\blog\storage\PersistentPostRepository;
use rtens\domin\ActionRegistry;
use rtens\domin\delivery\cli\CliApplication;
use rtens\domin\delivery\web\menu\Menu;
use rtens\domin\delivery\web\menu\MenuGroup;
use rtens\domin\delivery\web\menu\MenuItem;
use rtens\domin\delivery\web\renderers\link\ClassLink;
use rtens\domin\delivery\web\renderers\link\IdentifierLink;
use rtens\domin\delivery\web\renderers\link\LinkRegistry;
use rtens\domin\delivery\web\renderers\tables\DataTable;
use rtens\domin\delivery\web\renderers\tables\ObjectTable;
use rtens\domin\delivery\web\WebApplication;
use rtens\domin\execution\RedirectResult;
use rtens\domin\parameters\Html;
use rtens\domin\parameters\IdentifiersProvider;
use rtens\domin\reflection\CommentParser;
use rtens\domin\reflection\GenericMethodAction;
use rtens\domin\reflection\GenericObjectAction;
use rtens\domin\reflection\MethodActionGenerator;
use rtens\domin\reflection\ObjectActionGenerator;
use rtens\domin\reflection\types\TypeFactory;
use watoki\factory\Factory;

class Admin {

    public static function initWeb(WebApplication $app, $storageDir) {
        (new Admin($storageDir, $app->factory))
            ->initActions($app->actions, $app->types, $app->parser)
            ->initLinks($app->links)
            ->initIdentifierProviders($app->identifiers)
            ->initMenu($app->menu);
    }

    public static function initCli(CliApplication $app, $storageDir) {
        (new Admin($storageDir, $app->factory))
            ->initActions($app->actions, $app->types, $app->parser, true);
    }

    public function __construct($storageDir, Factory $factory) {
        $this->factory = $factory;

        $this->authors = new PersistentAuthorRepository($storageDir);
        $this->posts = new PersistentPostRepository($storageDir);
        $this->postService = $factory->setSingleton(new Posts($this->authors, $this->posts));
        $this->authorService = $factory->setSingleton(new Authors($this->authors));
    }

    private function initActions(ActionRegistry $actions, TypeFactory $types, CommentParser $parser, $cli = false) {
        $this->initPostActions($actions, $types, $parser, $cli);
        $this->initAuthorActions($actions, $types, $parser, $cli);

        return $this;
    }

    private function initPostActions(ActionRegistry $actions, TypeFactory $types, CommentParser $parser, $cli) {
        $postExecute = function ($object) {
            $methodName = 'handle' . (new \ReflectionClass($object))->getShortName();
            return call_user_func([$this->postService, $methodName], $object);
        };

        $demoExecute = function ($object) {
            return $object;
        };

        (new ObjectActionGenerator($actions, $types, $parser))
            ->fromFolder(__DIR__ . '/model/commands/demo', $demoExecute)
            ->fromFolder(__DIR__ . '/model/commands/post', $postExecute)
            ->configure(WritePost::class, function (GenericObjectAction $action) {
                $action->setAfterExecute(function (Post $post) {
                    return new RedirectResult('showPost', ['id' => $post->getId()]);
                });
            })
            ->configure(DeletePost::class, function (GenericObjectAction $action) {
                $action->setAfterExecute(function () {
                    return new RedirectResult('listPosts');
                });
            })
            ->configure(UpdatePost::class, function (GenericObjectAction $action) {
                $action->setFill(function ($parameters) {
                    if ($parameters['id'] && empty($parameters['title'])) {
                        $post = $this->posts->read($parameters['id']);
                        $parameters['title'] = $post->getTitle();
                        $parameters['text'] = $post->getText();
                    }
                    return $parameters;
                });
            })
            ->configure(ChangePostTags::class, function (GenericObjectAction $action) {
                $action->setFill(function ($parameters) {
                    if (!empty($parameters['id'])) {
                        $post = $this->posts->read($parameters['id']);
                        if (empty($parameters['tags'])) {
                            $parameters['tags'] = $post->getTags();
                        }
                    }
                    return $parameters;
                });
            })
            ->configure(ListPosts::class, function (GenericObjectAction $action) use ($types, $cli) {
                $action->setAfterExecute(function ($posts) use ($types, $cli) {
                    if ($cli) {
                        return (new ObjectTable($posts, $types))
                            ->selectProperties(['id', 'title', 'published', 'author'])
                            ->setHeader('published', 'Up');
                    } else {
                        return new DataTable((new ObjectTable($posts, $types))
                            ->selectProperties(['title', 'published', 'publishDate', 'text', 'author'])
                            ->setHeader('published', 'Up')
                            ->setFilter('text', function (Html $text) {
                                return substr(strip_tags($text->getContent()), 0, 50) . '...';
                            }));
                    }
                });
            });
    }

    private function initAuthorActions(ActionRegistry $actions, TypeFactory $types, CommentParser $parser, $cli) {
        (new MethodActionGenerator($actions, $types, $parser))
            ->fromObject($this->authorService)
            ->configure($this->authorService, 'changeName', function (GenericMethodAction $action) {
                $action->setFill(function ($parameters) {
                    if ($parameters['email']) {
                        $author = $this->authors->read($parameters['email']);
                        $parameters['name'] = $author->getName();
                    }
                    return $parameters;
                });
            })
            ->configure($this->authorService, 'register', function (GenericMethodAction $action) {
                $action->setAfterExecute(function (Author $author) {
                    $actionId = MethodActionGenerator::actionId(Authors::class, 'show');
                    return new RedirectResult($actionId, ['email' => $author->getEmail()]);
                });
            })
            ->configure($this->authorService, 'all', function (GenericMethodAction $action) use ($types, $cli) {
                if ($cli) {
                    $action->setAfterExecute(function ($authors) use ($types) {
                        return new ObjectTable($authors, $types);
                    });
                }
            });
    }

    private function initLinks(LinkRegistry $links) {
        $postParameters = function (Post $post) {
            return ['id' => $post->getId()];
        };

        $links->add($this->makeAuthorLink('show'));
        $links->add($this->makeAuthorLink('changePicture'));
        $links->add($this->makeAuthorLink('changeName'));

        $links->add(new ClassLink(Author::class, 'listPosts', function (Author $author) {
            return ['author' => $author->getEmail()];
        }));
        $links->add(new ClassLink(Post::class, 'showPost', $postParameters));
        $links->add(new ClassLink(Post::class, 'changePostTags', $postParameters));
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

        $links->add(new IdentifierLink(Author::class, MethodActionGenerator::actionId(Authors::class, 'show'), 'email'));
        $links->add(new IdentifierLink(Author::class, 'listPosts', 'author'));

        $links->add(new ClassLink(Bar::class, 'demoTables'));
        $links->add(new ClassLink(Bar::class, 'demoCharts'));

        return $this;
    }

    private function makeAuthorLink($method) {
        $actionId = MethodActionGenerator::actionId(Authors::class, $method);

        return new ClassLink(Author::class, $actionId, function (Author $author) {
            return ['email' => $author->getEmail()];
        });
    }

    private function initMenu(Menu $menu) {
        $menu->add(new MenuItem('writePost'));
        $menu->add(new MenuItem('listPosts'));
        $menu->addGroup((new MenuGroup('Authors'))
            ->add(new MenuItem(MethodActionGenerator::actionId(Authors::class, 'register')))
            ->add(new MenuItem(MethodActionGenerator::actionId(Authors::class, 'all')))
        );

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