<?php

namespace app\services\carts;

use app\command\cart\CartAddCommand;
use app\command\cart\CartRemoveCommand;
use app\command\cart\CartUpdateCommand;
use app\command\CommandBusInterface;
use app\exceptions\InvalidValidateException;
use app\exceptions\NotFoundException;
use app\repositories\carts\CartGoodRepository;
use app\repositories\carts\CartRepository;
use yii\helpers\Json;
use Yii;

class CartService
{
    private $commandBus;
    private $cartRepository;
    private $cartGoodRepository;

    public function __construct(
        CommandBusInterface $commandBus,
        CartRepository $cartRepository,
        CartGoodRepository $cartGoodRepository
    ) {
        $this->commandBus = $commandBus;
        $this->cartRepository = $cartRepository;
        $this->cartGoodRepository = $cartGoodRepository;
    }

    public function add()
    {
        return array_map(function ($item) {
            $cartAddCommand = CartAddCommand::buildByRequest($item);

            if (!$cartAddCommand->validate()) {
                throw new InvalidValidateException(
                    Json::encode($cartAddCommand->getErrors()),
                    self::class,
                    __METHOD__
                );
            }
            return $this->commandBus->execute($cartAddCommand);
        }, $this->decode());
    }

    public function get()
    {
        $cart = $this->cartRepository->exist();
        if (is_null($cart)) {
            throw new NotFoundException(
                Yii::t('app', 'Нет активной корзины'),
                self::class,
                __METHOD__
            );
        }
        return $cart;
    }

    public function remove()
    {
        return array_map(function ($item) {
            $cartRemoveCommand = CartRemoveCommand::buildByRequest($item);

            if (!$cartRemoveCommand->validate()) {
                throw new InvalidValidateException(
                    Json::encode($cartRemoveCommand->getErrors()),
                    self::class,
                    __METHOD__
                );
            }
            return $this->commandBus->execute($cartRemoveCommand);
        }, $this->decode());
    }

    public function clear()
    {
        $cart = $this->cartRepository->exist();
        if (is_null($cart)) {
            throw new NotFoundException(
                Yii::t('app', 'Нет активной корзины'),
                self::class,
                __METHOD__
            );
        }
        $countRemoveGoods = $this->cartGoodRepository->deleteAll($cart->id);
        if ($countRemoveGoods === 0) {
            throw new NotFoundException(
                Yii::t('app', 'Корзина уже пустая'),
                self::class,
                __METHOD__
            );
        }
        return $cart;
    }

    public function update()
    {
        return array_map(function ($item) {
            $cartUpdateCommand = CartUpdateCommand::buildByRequest($item);

            if (!$cartUpdateCommand->validate()) {
                throw new InvalidValidateException(
                    Json::encode($cartUpdateCommand->getErrors()),
                    self::class,
                    __METHOD__
                );
            }
            return $this->commandBus->execute($cartUpdateCommand);
        }, $this->decode());
    }

    private function decode()
    {
        try {
            return Json::decode(Yii::$app->request->getValueByMethod('composition'));
        } catch (\Exception $e) {
            throw new \Exception(Yii::t('app', 'Не корректный JSON'));
        }
    }
}
