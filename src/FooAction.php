<?php
namespace rtens\dominsample;

use rtens\domin\Action;
use watoki\collections\Map;

class FooAction implements Action {

    private $params;

    private $caption;

    public function __construct($caption, $params) {
        $this->caption = $caption;
        $this->params = $params;
    }

    public function caption() {
        return $this->caption;
    }

    public function parameters() {
        return new Map($this->params);
    }

    public function execute(Map $parameters) {
        $parameters = $parameters->filter(function ($item) {
            return !!$item;
        });
        $missingParams = array_diff($this->parameters()->keys()->toArray(), $parameters->keys()->toArray());
        if ($missingParams) {
            throw new \Exception("Missing parameters: " . implode(', ', $missingParams));
        }
        return $parameters->asList()->join(", ");
    }
}