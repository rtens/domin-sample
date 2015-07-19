## domin sample ##

This project serves as an example on how to use [domin] to generate a administrative user interface. A demo is deployed on http://domin.rtens.org. The project mimics a simple blog and there is also a *demo action* showcasing all available input elements.

[domin]: https://github.com/rtens/domin


## Action! ##

The core concept of *domin* are [`Actions`], which decide what [`Parameters`] it needs, how to `fill()` them with default values and how to `execute()` it. So in order to use *domin*, you need to put all action into the [`ActionRegistry`], which is done by the [`Admin`] class using the `MethodActionGenerator`, but it could be done in several other ways:

[`Actions`]: https://github.com/rtens/domin/blob/master/src/Action.php
[`Parameters`]: https://github.com/rtens/domin/blob/master/src/Parameter.php
[`ActionRegistry`]: https://github.com/rtens/domin/blob/master/src/ActionRegistry.php
[`Admin`]: https://github.com/rtens/domin-sample/blob/master/src/admin/Admin.php

#### Implementing `Action` ####

The most straight-forward although probably not most convenient way is to create an implementation of `Action` for every ability of the system.

```php
class MyAction implements Action {

    public function caption() {
        return 'Some Action';
    }

    public function parameters() {
        return [
            new Parameter('foo', new StringType()),
            new Parameter('bar', new ClassType(\DateTime::class))
        ];
    }

    public function fill(array $parameters) {
        $parameters['foo'] = 'default value of foo';
        return $parameters;
    }

    public function execute(array $parameters) {
        var_dump("Make it so!", $parameters);
    }
}

$actionRegistry->add('my', new MyAction());
```

#### Extending `ObjectAction` ####

If you represent abilities with [DTOs], you can extend you actions from the `ObjectAction` to infer `Parameters` from the properties of these classes using reflection. This sub-class can then be made generic for example by using a [Command Bus].

```php
class MyAction extends ObjectAction {
    
    public function __construct($class, TypeFactory $types, CommandBus $bus) {
        parent::__construct($class, $types);
        $this->bus = $bus;
    }

    protected function executeWith($object) {
        $this->bus->handle($object);
    }
}

$actionRegistry->add('my', new MyAction(MyCommand::class, $types, $bus));
$actionRegistry->add('your', new MyAction(YourCommand::class, $types, $bus));
$actionRegistry->add('their', new MyAction(TheirCommand::class, $types, $bus));
```

[DTOs]: https://en.wikipedia.org/wiki/Data_transfer_object
[Command Bus]: http://tactician.thephpleague.com/


#### Generating `ObjectActions` ####

With a generic way to execute actions, you can use the `ObjectActionGenerator` to generate and register actions from all classes in a folder automatically.

```php
(new ObjectActionGenerator($actionRegistry))->fromFolder('model/commands', function ($object) {
    $bus->handle($object);
});
```

#### Using `MethodAction` ####

If you don't feel like creating a class for every command, the `MethodAction` can infer parameters from a method signature.

```php
$actionRegistry->add('my', new MethodAction($handler, 'handleMyCommand'));
$actionRegistry->add('your', new MethodAction($handler, 'handleYourCommand'));
$actionRegistry->add('their', new MethodAction($handler, 'handleTheirCommand'));
```

#### Generating `MethodActions` ####

There is also a `MethodActionGenerator` to register all methods of an object.

```php
(new MethodActionGenerator($actionRegistry))->fromObject($handler);
```


## Installation ##

Download the project with [git] and build it with [composer]

    git clone https://github.com/rtens/domin-sample.git
    cd domin-sample
    composer install
    
Start a development server to run the web application on [localhost:8080](http://localhost:8080)

    php -S localhost:8080 index.php
    
To run the CLI application, enter
    
    php cli.php

[composer]: http://getcomposer.org
[git]: http://git-scm.org
