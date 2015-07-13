<?php
namespace rtens\blog\admin;

use rtens\blog\Application;
use rtens\blog\model\commands\ChangeAuthorName;
use rtens\blog\model\commands\ChangeAuthorPicture;
use rtens\blog\model\commands\RegisterAuthor;
use rtens\blog\model\commands\UpdatePost;
use rtens\blog\model\commands\WritePost;
use rtens\blog\model\queries\ListAuthors;
use rtens\blog\model\queries\ListPosts;
use rtens\blog\storage\PersistentAuthorRepository;
use rtens\blog\storage\PersistentPostRepository;
use rtens\domin\ActionRegistry;
use rtens\domin\delivery\FieldRegistry;
use rtens\domin\delivery\RendererRegistry;
use rtens\domin\web\fields\BooleanField;
use rtens\domin\web\fields\FileField;
use rtens\domin\web\fields\HtmlField;
use rtens\domin\web\fields\StringField;
use rtens\domin\web\renderers\ArrayRenderer;
use rtens\domin\web\renderers\BooleanRenderer;
use rtens\domin\web\renderers\DateTimeRenderer;
use rtens\domin\web\renderers\FileRenderer;
use rtens\domin\web\renderers\HtmlRenderer;
use rtens\domin\web\renderers\ObjectRenderer;
use rtens\domin\web\renderers\PrimitiveRenderer;
use watoki\factory\Factory;

class Admin {

    public static function init($storageDir, Factory $factory = null) {
        $pictureDir = $storageDir . '/pictures';
        $factory = $factory ? : new Factory();

        $handler = new Application(
            new PersistentAuthorRepository($storageDir),
            new PersistentPostRepository($storageDir)
        );

        $actions = $factory->setSingleton(new ActionRegistry());
        $actions->add('registerAuthor', new CommandAction(RegisterAuthor::class, $handler));
        $actions->add('listAuthors', new CommandAction(ListAuthors::class, $handler));
        $actions->add('changeAuthorPicture', new CommandAction(ChangeAuthorPicture::class, $handler));
        $actions->add('changeAuthorName', new CommandAction(ChangeAuthorName::class, $handler));
        $actions->add('writePost', new CommandAction(WritePost::class, $handler));
        $actions->add('listPosts', new CommandAction(ListPosts::class, $handler));
        $actions->add('updatePost', new CommandAction(UpdatePost::class, $handler));

        $fields = $factory->setSingleton(new FieldRegistry());
        $fields->add(new StringField());
        $fields->add(new BooleanField());
        $fields->add(new FileField());
        $fields->add(new HtmlField());

        $renderers = $factory->setSingleton(new RendererRegistry());
        $renderers->add(new FileRenderer($pictureDir));
        $renderers->add(new DateTimeRenderer());
        $renderers->add(new HtmlRenderer());
        $renderers->add(new ArrayRenderer($renderers));
        $renderers->add(new ObjectRenderer($renderers));
        $renderers->add(new BooleanRenderer());
        $renderers->add(new PrimitiveRenderer());

        return $factory;
    }
}