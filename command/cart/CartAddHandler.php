<?php

namespace app\command\cart;

use app\command\CommandTrait;
use app\models\carts\CartGood;

class CartAddHandler extends CartCommandHandler
{
    use CommandTrait;

    public function handle(CartAddCommand $command)
    {
        $this->validate($command);

        $this->transactionManager->wrap(function () use ($command) {
            $this->getOrCreateCart($command);
            $good = $this->getGoodByHash($command);
            $this->addGood($command, $good);
            $this->cartGoodRepository->save($command->good);
        });
        $this->response->setData($command->good);

        return $this->response;
    }

    public function getGoodByHash(CartAddCommand $command)
    {
        return $this->cartGoodRepository->getByHash(
            $command->cart->id,
            CartGood::createHash($command->cart->id, $command)
        );
    }

    public function addGood(CartAddCommand $command, $good)
    {
        $command->setGood(
            is_null($good) ?
                (new CartGood())->add($command) :
                $good->incrementCount($command->count)
        );
    }
}
