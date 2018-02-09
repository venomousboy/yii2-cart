<?php

namespace app\command\cart;

class CartAddValidator extends CartCommandValidator
{
    public function validate(CartAddCommand $command)
    {
        $this->hasByGoodId($command->good_id);
        $this->hasByIngredients($command->ingredients);
        $this->hasByGoodType($command->type, $command->level);
        $this->response->setErrors($this->errors);

        return $this->response;
    }
}
