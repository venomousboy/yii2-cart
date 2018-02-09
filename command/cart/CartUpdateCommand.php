<?php

namespace app\command\cart;

use app\command\CommandInterface;
use app\models\carts\Cart;
use app\models\carts\CartGood;
use yii\base\Model;

final class CartUpdateCommand extends Model implements CommandInterface
{
    public $hash;
    public $good_id;
    public $ingredients;
    public $type;
    public $count;
    public $level;
    public $good;
    public $cart;

    public function __construct(
        $hash,
        $goodId,
        $ingredients,
        $type,
        $count,
        $level,
        array $config = []
    ) {
        $this->hash = $hash;
        $this->good_id = $goodId;
        $this->ingredients = $ingredients;
        $this->type = $type;
        $this->count = $count;
        $this->level = $level;
        parent::__construct($config);
    }

    public static function buildByRequest($item)
    {
        return new self(
            $item['hash'] ?? null,
            $item['good_id'] ?? null,
            $item['ingredients'] ?? null,
            $item['type'] ?? null,
            $item['count'] ?? null,
            $item['level'] ?? null
        );
    }

    public function rules()
    {
        return [
            [['good_id', 'type', 'hash'], 'required'],
            ['type', 'in', 'range' => array_keys(CartGood::checkType())],
            [['good_id', 'count', 'level'], 'integer'],
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
