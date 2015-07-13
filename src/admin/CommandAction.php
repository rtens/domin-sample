<?php
namespace rtens\blog\admin;

use rtens\blog\Application;
use rtens\domin\reflection\ObjectAction;

class CommandAction extends ObjectAction {

    /** @var Application */
    private $handler;

    function __construct($commandClass, Application $handler) {
        parent::__construct($commandClass);
        $this->handler = $handler;
    }

    protected function executeWith($object) {
        $methodName = 'handle' . $this->class->getShortName();
        return call_user_func([$this->handler, $methodName], $object);
    }

    /**
     * Fills out partially available parameters
     *
     * @param array $parameters Available values indexed by name
     * @return array Filled values indexed by name
     */
    public function fill(array $parameters) {
        $methodName = 'fill' . $this->class->getShortName();
        if (method_exists($this->handler, $methodName)) {
            return call_user_func([$this->handler, $methodName], $parameters);
        } else {
            return $parameters;
        }
    }
}