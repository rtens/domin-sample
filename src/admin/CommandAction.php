<?php
namespace rtens\blog\admin;

use rtens\blog\Application;
use rtens\domin\reflection\ObjectAction;

class CommandAction extends ObjectAction {

    /** @var Application */
    private $handler;
    private $afterExecuted;

    function __construct($commandClass, Application $handler) {
        parent::__construct($commandClass);
        $this->handler = $handler;
    }

    /**
     * @param callable $callback Receives the command object and the value returned by the handler
     * @return $this
     */
    public function setAfterExecuted(callable $callback) {
        $this->afterExecuted = $callback;
        return $this;
    }

    protected function executeWith($object) {
        $methodName = 'handle' . $this->class->getShortName();
        $returned = call_user_func([$this->handler, $methodName], $object);

        if ($this->afterExecuted) {
            return call_user_func($this->afterExecuted, $object, $returned);
        } else {
            return $returned;
        }
    }

    /**
     * Fills out partially available parameters
     *
     * @param array $parameters Available values indexed by name
     * @return array Filled values indexed by name
     */
    public function fill(array $parameters) {
        $parameters = parent::fill($parameters);

        $methodName = 'fill' . $this->class->getShortName();
        if (method_exists($this->handler, $methodName)) {
            return call_user_func([$this->handler, $methodName], $parameters);
        } else {
            return $parameters;
        }
    }
}