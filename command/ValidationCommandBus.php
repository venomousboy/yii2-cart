<?php

namespace app\command;

use app\exceptions\InvalidValidateException;
use app\models\logs\LoggerInterface;
use yii\helpers\Json;
use Yii;

class ValidationCommandBus implements CommandBusInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute($command)
    {
        $validator = $this->resolveValidator($command);
        $callback = call_user_func($validator, $command);

        if (sizeof($callback->getErrors())) {
            // Запишем логи
            $this->logger->log(
                $callback->getLog(),
                $callback->getEvent(),
                substr(get_class($command), 0, -7),
                'validate',
                $callback->getErrors()
            );

            throw new InvalidValidateException(
                Json::encode($callback->getErrors()),
                self::class,
                __METHOD__
            );
        }
    }

    private function resolveValidator($command)
    {
        return [Yii::createObject(substr(get_class($command), 0, -7) . 'Validator'), 'validate'];
    }
}
