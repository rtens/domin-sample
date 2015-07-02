<?php
namespace rtens\dominsample;

use rtens\domin\delivery\Field;

class FooField implements Field {

    public function handles($type) {
        return true;
    }

    public function inflate($serialized) {
        return $serialized ? $serialized . '!' : null;
    }
}