<?php

namespace App\Point;

use App\Enums\StatusCode;
use App\Models\PointCustomer;
use App\Models\StoreEmployee;
use App\Point\Calculator\ConsumptionPointCalculator;
use App\Point\Calculator\PointCalculatorFactory;
use App\Point\Calculator\ScanStorePointCalculator;
use App\Point\Converter\PointConverterFactory;
use App\Point\Calculator\PointCalculator;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PointService
{
    private array $observers = [];

    public function attachObserver($observer): void
    {
        $this->observers[] = $observer;
    }

    /**
     * @throws \Exception
     */
    public function createPoint($source, $customer, $reference = null, $operator = null, $data = null): ?PointCustomer
    {
        $calculator = PointCalculatorFactory::create($source);
        $memo = PointEnums::getSourceName($source);

        if ($calculator instanceof ScanStorePointCalculator) {
            if ($reference == null) {
                throw new InvalidArgumentException("錯誤來源類型.");
            }

            $this->checkIfCustomerReceivedScanStorePoint($customer->id, $reference);
        }

        if ($calculator instanceof ConsumptionPointCalculator) {
            $converter = PointConverterFactory::create($source);
            $amount = $converter->convert($data);
            $memo = str_replace(":price", $amount, $memo);
        }

        $point = $calculator->calculate($amount ?? null);
        $memo = str_replace(":point", $point, $memo);

        try {
            DB::beginTransaction();;

            $customer->point_balance += $point;

            $pointCustomer = new PointCustomer([
                'customer_id' => $customer->id,
                'value' => $point,
                'memo' => $memo ?? null,
                'source' => $source,
                'point_balance' => $customer->point_balance,
            ]);

            if($operator) {
                $pointCustomer->operator()->associate($operator);
            }

            if($reference) {
                $pointCustomer->reference()->associate($reference);
            }

            $pointCustomer->save();

            $customer->save();

            DB::commit();

            $this->notifyObservers($customer, $pointCustomer);

            return $pointCustomer;
        } catch (\Exception $err) {
            DB::rollBack();

            throw new Exception($err);
        }
    }

    public function getPointBalance($customer)
    {
       return $customer->point_balance;
    }

    public function hasEnoughPoints($customer, $points): bool
    {
        // 檢查使用者是否擁有足夠的點數進行兌換
        return $this->getPointBalance($customer) >= $points;
    }

    public function deductPoints($customer, $points, $remark = null): PointCustomer
    {
        try {
            DB::beginTransaction();;

            $customer->point_balance -= $points;

            $pointCustomer = new PointCustomer([
                'customer_id' => $customer->id,
                'value' => $points,
                'is_redeem' => true,
                'remark' => $remark ?? null,
                'source' => PointEnums::SOURCE_REDEEM_STAMP,
                'point_balance' => $customer->point_balance,
            ]);

            $operator = StoreEmployee::first();
            $pointCustomer->operator()->associate($operator);
            $pointCustomer->reference()->associate($operator->store);

            $pointCustomer->save();

            $customer->point_balance -= $points;
            $customer->save();

            DB::commit();

            $this->notifyObservers($customer, $pointCustomer);

            return $pointCustomer;
        } catch (\Exception $err) {
            DB::rollBack();

            throw new Exception($err);
        }
    }

    /**
     * @throws Exception
     */
    private function checkIfCustomerReceivedScanStorePoint($customerId, $reference): void
    {
        if (PointCustomer::where('source', PointEnums::SOURCE_SCAN_STORE)
            ->where('reference_type', $reference->getMorphClass())
            ->where('reference_id', $reference->id)
            ->where('customer_id', $customerId)
            ->exists()) {
            throw new Exception("已經獲得過點數.", StatusCode::POINT_REPEAT_STORE_SCANNED->value);
        }
    }

    private function notifyObservers($customer, $point): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($customer, $point);
        }
    }
}
