<?php

namespace app\command;

use app\models\carts\Cart;

interface CommandInterface
{
    public static function buildByRequest($item);
    public function setCart(Cart $cart);
}
