<?php
namespace rtens\dominsample;

use rtens\domin\delivery\ParameterReader;

class RequestParameterReader implements ParameterReader {

    /**
     * @param string $name
     * @return string The serialized paramater
     */
    public function read($name) {
        return isset($_GET['params'][$name]) ? $_GET['params'][$name] : null;
    }
}