<?php

require_once __DIR__ . "/vendor/autoload.php";

use rtens\domin\ActionRegistry;
use rtens\domin\delivery\FieldRegistry;
use rtens\domin\delivery\RendererRegistry;
use rtens\domin\Executor;
use rtens\dominsample\CliParameterReader;
use rtens\dominsample\FooAction;
use rtens\dominsample\FooField;
use rtens\dominsample\FooRenderer;

function readline($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

$actions = new ActionRegistry();
$fields = new FieldRegistry();
$renderers = new RendererRegistry();

$actions->add('foo', new FooAction("Foo", ['one' => 'foo', 'two' => 'foo', 'three' => 'foo']));
$actions->add('bar', new FooAction("Bar", ['me' => 'foo', 'you' => 'foo']));
$fields->add(new FooField());
$renderers->add(new FooRenderer());

$executor = new Executor($actions, $fields, $renderers, new CliParameterReader());

while (true) {

    echo "--- Chose an action (empty to quit) ---" . PHP_EOL;
    foreach ($actions->getAllActions() as $id => $action) {
        echo "$id: " . $action->caption() . PHP_EOL;
    }

    echo PHP_EOL;

    $actionId = readline('Action: ');

    if (!$actionId) {
        exit;
    }

    echo PHP_EOL;

    echo '-> ' . $executor->execute($actionId);

    echo PHP_EOL . PHP_EOL;
}