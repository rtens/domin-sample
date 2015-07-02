<?php
namespace rtens\dominsample;

use rtens\domin\delivery\ParameterReader;

class CliParameterReader implements ParameterReader {

    public function read($name) {
        return readline("$name: ");
    }
}