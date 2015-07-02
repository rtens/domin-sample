<?php
namespace rtens\dominsample;

use rtens\domin\delivery\Renderer;

class FooRenderer implements Renderer {

    public function handles($value) {
        return true;
    }

    public function render($value) {
        return '(' . $value . ')';
    }
}