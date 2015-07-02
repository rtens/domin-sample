<?php

require_once __DIR__ . "/vendor/autoload.php";

use rtens\domin\ActionRegistry;
use rtens\domin\delivery\FieldRegistry;
use rtens\domin\delivery\RendererRegistry;
use rtens\domin\Executor;
use rtens\dominsample\FooAction;
use rtens\dominsample\FooField;
use rtens\dominsample\FooRenderer;
use rtens\dominsample\RequestParameterReader;


#### Set-up ####

$reader = new RequestParameterReader();
$actions = new ActionRegistry();
$fields = new FieldRegistry();
$renderers = new RendererRegistry();

$actions->add('foo', new FooAction("Foo", ['one' => 'bar', 'two' => 'foo', 'three' => 'foo']));
$actions->add('bar', new FooAction("Bar", ['this' => 'bar', 'that' => 'bar']));
$fields->add(new FooField());
$renderers->add(new FooRenderer());

#### Select Action ####

if (!isset($_GET['action'])) {
    echo "<ul>";
    foreach ($actions->getAllActions() as $name => $action) {
        echo "<li><a href='?action=$name'>" . $action->caption() . '</a></li>';
    }
    echo "</ul>";
    exit;
}

echo "<p><a href='web.php'>&laquo; List</a></p>";

$actionId = $_GET['action'];
$action = $actions->getAction($actionId);

#### Show form ####

echo "<form method='get'><input type='hidden' name='action' value='$actionId'>";
foreach ($action->parameters() as $name => $type) {
    $field = $fields->getField($type);
    $value = $reader->read($name);
    echo "<div><label>$name <input type='text' name='params[$name]' value='$value'></label></div>";
}
echo '<input type="submit"></form>';

#### Execution ####

echo (new Executor($actions, $fields, $renderers, $reader))->execute($actionId);