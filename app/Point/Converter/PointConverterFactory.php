<?php

namespace App\Point\Converter;

use App\Point\PointEnums;
use Exception;

class PointConverterFactory
{
    /**
     * @throws Exception
     */
    public static function create($type): PointConverter
    {
        return match ($type) {
            PointEnums::SOURCE_CONSUMPTION_INVOICE => new InvoicePointConverter(),
            PointEnums::SOURCE_CONSUMPTION_INPUT => new InputPointConverter(),
            default => throw new Exception("Invalid converter type"),
        };
    }
}
