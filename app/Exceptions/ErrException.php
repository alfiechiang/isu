<?php

namespace App\Exceptions;

use Exception;

class ErrException extends Exception
{
    public function render($request)
    {       
        
        return response()->json(["code" => 40001, "message" => $this->getMessage(),"data"=>[]]);       
    }
}
