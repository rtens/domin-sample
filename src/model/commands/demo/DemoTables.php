<?php
namespace rtens\blog\model\commands\demo;

use rtens\blog\model\commands\demo\inner\Bar;
use rtens\domin\delivery\web\renderers\tables\types\ArrayTable;
use rtens\domin\delivery\web\renderers\tables\types\DataTable;
use rtens\domin\delivery\web\renderers\tables\types\ObjectTable;
use rtens\domin\reflection\types\TypeFactory;

/**
 * Demonstrates different kinds of rendering lists and tables
 *
 * You can find the code that is Action is generated from [here](http://github.com/rtens/domin-sample/blob/master/src/model/commands/demo/DemoTables.php)
 */
class DemoTables {

    private static function bar($foo, $bar) {
        $object = new Bar();
        $object->foo = $foo;
        $object->bar = $bar;
        return $object;
    }

    function getArray() {
        return [
            'one' => 'uno',
            'two' => 'dos',
            'three' => 'tres'
        ];
    }

    function getArrayList() {
        return [
            $this->getArray(),
            $this->getArray()
        ];
    }

    function getObjectList() {
        return [
            self::bar('uno', 'dos'),
            self::bar('un', 'deux'),
            self::bar('uno', 'due'),
        ];
    }

    function getArrayTable() {
        return new ArrayTable([
            ['one' => 'uno', 'two' => 'dos', 'three' => 'tres'],
            ['one' => 'un', 'two' => 'deux'],
            ['two' => 'due', 'three' => 'tre']
        ]);
    }

    function getObjectTable() {
        return new ObjectTable($this->getObjectList(), new TypeFactory());
    }

    function getDataObjectTable() {
        return new DataTable($this->getObjectTable());
    }

    function getExtensiveDataTable() {
        $data = [];
        for ($i = 0; $i < 53; $i++) {
            $data[] = [
                'foo' => substr(md5($i*100),0,5),
                'bar' => substr(md5($i*1000),0,6),
                'baz' => substr(md5($i*10000),0,3),
            ];
        }
        return new DataTable(new ArrayTable($data));
    }
}