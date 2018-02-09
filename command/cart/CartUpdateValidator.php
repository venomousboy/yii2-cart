<?php

namespace app\command\cart;

class CartUpdateValidator extends CartCommandValidator
{
    public function validate(CartUpdateCommand $command)
    {
        $this->hasCartAndGoodForModify($command);
        $this->hasByGoodId($command->good_id);
        $this->hasByIngredients($command->ingredients);
        $this->hasByGoodType($command->type, $command->level);
        $this->response->setErrors($this->errors);

        return $this->response;
    }
}
