<?php

namespace app\command\cart;

use app\command\CommandTrait;
use app\models\carts\CartGood;

class CartUpdateHandler extends CartCommandHandler
{
    use CommandTrait;

    public function handle(CartUpdateCommand $command)
    {
        $this->validate($command);

        $this->transactionManager->wrap(function () use ($command) {
            $this->cartGoodRepository->save(
                (new CartGood())->edit($command)
            );
        });
        $this->response->setData($command->good);

        return $this->response;
    }
}
