<?php

namespace App\Point\Converter;

class InvoicePointConverter implements PointConverter
{
    public function convert($data)
    {
        // 根據掃描的發票獲取金額
        return $this->getAmountFromInvoice($data);
    }

    private function getAmountFromInvoice($data)
    {
        $image = $data['image'];
        // 解析發票數據，返回總金額
    }
}
