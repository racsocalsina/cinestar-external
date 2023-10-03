<?php


namespace App\Services\PayU\Shared\Exceptions;


use Throwable;

class PayuException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
