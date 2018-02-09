<?php

namespace app\command\cart;

use app\command\CommandTrait;

class CartRemoveHandler extends CartCommandHandler
{
    use CommandTrait;

    public function handle(CartRemoveCommand $command)
    {
        $this->validate($command);

        $this->transactionManager->wrap(function () use ($command) {
            $good = $command->good;
            $this->cartGoodRepository->remove($good);
            $good->setDelete();
        });
        $this->response->setData($command->good);

        return $this->response;
    }
}
