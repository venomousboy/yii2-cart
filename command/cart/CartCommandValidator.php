<?php

namespace app\command\cart;

use app\command\CommandInterface;
use app\command\ResponseCommand;
use app\events\carts\CartEvent;
use app\models\AndromedaGoodItem;
use app\models\carts\CartGood;
use app\models\carts\CartLog;
use app\models\IngredientKind;
use app\repositories\carts\CartGoodRepository;
use app\repositories\carts\CartRepository;
use app\repositories\OperationRepository;
use Yii;

class CartCommandValidator
{
    private $operationRepository;
    private $cartRepository;
    private $cartGoodRepository;
    protected $response;

    protected $errors = [];

    public function __construct(
        OperationRepository $operationRepository,
        CartRepository $cartRepository,
        CartGoodRepository $cartGoodRepository,
        ResponseCommand $responseCommand
    ) {
        $this->operationRepository = $operationRepository;
        $this->cartRepository = $cartRepository;
        $this->cartGoodRepository = $cartGoodRepository;
        $this->response = $responseCommand;
        $this->response->setLog(CartLog::class);
        $this->response->setEvent(CartEvent::class);
    }

    protected function hasByGoodId($goodId)
    {
        if (!$this->operationRepository->hasById(new AndromedaGoodItem(), $goodId)) {
            $this->errors['good_id'] = [
                Yii::t('app', 'Товар не существует')
            ];
        }
    }

    protected function hasByIngredients($ingredients)
    {
        if (is_array($ingredients)) {
            foreach ($ingredients as $ingredient) {
                if (!$this->operationRepository->hasById(new IngredientKind(), $ingredient['id'])) {
                    $this->errors['ingredients'] = [
                        'id' => $ingredient['id'],
                        'description' => Yii::t('app', 'Ингредиент не существует'),
                    ];
                }
            }
        }
    }

    protected function hasByGoodType($type, $level)
    {
        if (!in_array($type, CartGood::checkType())) {
            $this->errors['type'] = [
                Yii::t('app', 'Не известный тип товара')
            ];
        }

        if ($type === CartGood::TYPE_COMBO && is_null($level)) {
            $this->errors['level'] = [
                Yii::t('app', 'Для типа комбобокс не указана позиция товара')
            ];
        }
    }

    protected function hasCartAndGoodForModify(CommandInterface $command)
    {
        if ($cart = $this->cartRepository->exist()) {
            $command->setCart($cart);
        } else {
            $this->errors['cart'] = [
                Yii::t('app', 'Активная корзина отсутствует')
            ];
        }

        if ($command->cart) {
            if ($good = $this->cartGoodRepository->getByHash($command->cart->id, $command->hash)) {
                $command->setGood($good);
            } else {
                $this->errors['hash'] = [
                    Yii::t('app', 'Товар не существует')
                ];
            }
        }
    }
}
