<?php

namespace App\Exceptions;

use Exception;

class StockInsufficientException extends Exception
{
    protected $details;

    public function __construct($message = "Stock insuficiente", $details = [], $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
