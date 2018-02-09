<?php

namespace app\command\cart;

use app\command\CommandInterface;
use app\command\ResponseCommand;
use app\events\carts\CartEvent;
use app\models\carts\Cart;
use app\models\carts\CartLog;
use app\repositories\carts\CartGoodRepository;
use app\repositories\carts\CartRepository;
use app\services\TransactionManager;

class CartCommandHandler
{
    protected $cartRepository;
    protected $cartGoodRepository;
    protected $transactionManager;
    protected $response;

    public function __construct(
        CartRepository $cartRepository,
        CartGoodRepository $cartGoodRepository,
        TransactionManager $transactionManager,
        ResponseCommand $responseCommand
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartGoodRepository = $cartGoodRepository;
        $this->transactionManager = $transactionManager;
        $this->response = $responseCommand;
        $this->response->setLog(CartLog::class);
        $this->response->setEvent(CartEvent::class);
    }

    public function getOrCreateCart(CommandInterface $command)
    {
        if ($cart = $this->cartRepository->exist()) {
            $command->setCart($cart);
        } else {
            $command->setCart((new Cart())->create());
            $this->cartRepository->save($command->cart);
        }
    }
}
