<?php

namespace App\EventListener;

use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use Overblog\GraphQLBundle\Event\ExecutorResultEvent;

class AppErrorHandler
{
    public function onPostExecutor(ExecutorResultEvent $event): void
    {
        $myErrorFormatter = static function(Error $error) {
            return FormattedError::createFromException($error);
        };

        $myErrorHandler = static function(array $errors, callable $formatter) {
            return array_map($formatter, $errors);
        };

        $event->getResult()
            ->setErrorFormatter($myErrorFormatter)
            ->setErrorsHandler($myErrorHandler);
    }
}
