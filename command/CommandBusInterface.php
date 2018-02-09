<?php

namespace app\command;

interface CommandBusInterface
{
    public function execute($command);
}
