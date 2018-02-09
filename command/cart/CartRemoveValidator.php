<?php

namespace app\command\cart;

class CartRemoveValidator extends CartCommandValidator
{
    public function validate(CartRemoveCommand $command)
    {
        $this->hasCartAndGoodForModify($command);
        $this->response->setErrors($this->errors);

        return $this->response;
    }
}
