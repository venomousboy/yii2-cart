<?php

namespace app\command;

use app\models\logs\LoggerInterface;
use Yii;

class CommandBus implements CommandBusInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute($command)
    {
        $handler = $this->resolveHandler($command);
        $callback = call_user_func($handler, $command);
        // Запишем логи
        $this->logger->log(
            $callback->getLog(),
            $callback->getEvent(),
            substr(get_class($command), 0, -7),
            'handle',
            $callback->getData()
        );

        return $callback->getData();
    }

    private function resolveHandler($command)
    {
        return [Yii::createObject(substr(get_class($command), 0, -7) . 'Handler'), 'handle'];
    }
}
