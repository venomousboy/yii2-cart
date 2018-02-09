<?php

namespace app\command;

trait CommandTrait
{
    protected function validate($command)
    {
        \Yii::createObject(ValidationCommandBus::class)->execute($command);
    }
}
