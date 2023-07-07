<?php
namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrException extends HttpException
{
    public function __construct($message = null, $code = 0, \Throwable $previous = null, array $headers = [], $statusCode = 400)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public function render($request)
    {       
        return response()->json([
            "code" => 40001,
            "message" => $this->getMessage(),
            "data" => []
        ]);    
    }
}