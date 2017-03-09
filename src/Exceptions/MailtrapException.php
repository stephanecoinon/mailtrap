<?php

namespace StephaneCoinon\Mailtrap\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException;

class MailtrapException extends Exception
{
    public $status;
    public $error;

    public static function create(RequestException $exception)
    {
        $response = $exception->getResponse();
        $instance = new static;

        $instance->status = $response->getStatusCode();
        $instance->error = $response->getReasonPhrase();

        return $instance;
    }
}
