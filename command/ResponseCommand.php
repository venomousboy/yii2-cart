<?php

namespace app\command;

use app\models\carts\CartGood;

class ResponseCommand
{
    private $data;
    private $errors;
    private $log;
    private $event;

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param mixed $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

    /**
     * @return CartGood
     */
    public function getData(): CartGood
    {
        return $this->data;
    }

    /**
     * @param CartGood $data
     */
    public function setData(CartGood $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
}
