<?php

namespace App\Point;

use InvalidArgumentException;

class PointEnums
{
    const SOURCE_REDEEM_STAMP = 'redeem_stamp';
    const SOURCE_SCAN_STORE = 'scan_store';
    const SOURCE_CONSUMPTION_INVOICE = 'consumption_invoice';
    const SOURCE_CONSUMPTION_INPUT = 'consumption_input';

    public static function getSourceName($source): string
    {
        return match ($source) {
            self::SOURCE_SCAN_STORE => '掃描店家獲得點數 :point',
            self::SOURCE_CONSUMPTION_INVOICE => '消費認證_掃描發票金額 $:price 獲得點數 :point',
            self::SOURCE_CONSUMPTION_INPUT => '消費認證_店家輸入金額 $:price 獲得點數 :point',
            self::SOURCE_REDEEM_STAMP => '兌換集章',
            default => '',
        };
    }
}
