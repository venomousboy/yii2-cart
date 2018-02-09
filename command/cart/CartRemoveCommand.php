<?php

namespace app\command\cart;

use app\command\CommandInterface;
use app\models\carts\Cart;
use app\models\carts\CartGood;
use yii\base\Model;

final class CartRemoveCommand extends Model implements CommandInterface
{
    public $hash;
    public $good;
    public $cart;

    public function __construct(
        $hash,
        array $config = []
    ) {
        $this->hash = $hash;
        parent::__construct($config);
    }

    public static function buildByRequest($item)
    {
        return new self(
            $item['hash'] ?? null
        );
    }

    public function rules()
    {
        return [
            ['hash', 'required'],
            ['hash', 'string', 'max' => 255],
        ];
    }

    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function setGood(CartGood $good)
    {
        $this->good = $good;
    }
}
