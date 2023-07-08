<?php

namespace App\Services\Customers;

use App\Exceptions\ErrException;
use App\Models\PointCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\Stamp;



class PointService
{

    protected StampService $stampService;


    public function __construct(StampService $stampService)
    {
        $this->stampService = $stampService;
    }

    public function list()
    {

        $auth = Auth::user();
        return PointCustomer::where('customer_id', $auth->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function pageList($data)
    {
        $auth = Auth::user();
        return PointCustomer::where('customer_id', $auth->id)
            ->orderBy('created_at', 'desc')
            ->paginate($data['per_page']);
    }

    public function totalPoints()
    {
        $auth = Auth::user();
        $rows =  PointCustomer::select('customer_id', DB::raw('SUM(value) as total'))
            ->where('customer_id', $auth->id)->where('is_redeem', false)
            ->groupBy('customer_id')->get();

        $total = intval($rows[0]->total);

        return ['total' => $total];
    }

    public function exchangeToStamps($data)
    {

        $auth = Auth::user();
        $customer_id = $auth->id;
        $rows = PointCustomer::where('customer_id', $customer_id)->orderBy('created_at', 'asc')
            ->get();

        $exchangePoints = $data['exchangePoints'];

        $stamps_num = $data['exchangePoints'] / Stamp::ONESTAMPVALUEPOINTS->value;
        $point_ids = [];

        foreach ($rows as $row) {

            if ($row->value <= $exchangePoints) {

                if ($exchangePoints - $row->value >= 0) {

                    $exchangePoints -= $row->value;

                    $point_ids[] = $row->id;
                }
            }
            if ($exchangePoints == 0) {

                break;
            }
        }

        if ($exchangePoints !== 0) {
            throw new ErrException('點數無法兌換此數量集章');
        }

        DB::transaction(function () use ($point_ids, $stamps_num, $customer_id) {
            PointCustomer::whereIn('id', $point_ids)->update(['is_redeem' => true]);
            $this->stampService->pointExchange(['stamps_num' => $stamps_num, 'customer_id' => $customer_id]);
        });
    }
}
