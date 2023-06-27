<?php

namespace App\Point\Converter;

class InputPointConverter implements PointConverter
{
    public function convert($data)
    {
        // 從表單數據獲取金額
        return $data['amount'];
    }
}
