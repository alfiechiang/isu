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
        $Builder =PointCustomer::where('customer_id', $auth->id);
        if(isset($data['sort'])){
            switch ($data['sort']){
                case 1:
                    $Builder=$Builder->orderBy('created_at', 'desc');
                    break;
                case 2:
                    $Builder=$Builder->orderBy('created_at', 'asc');
                    break;
            }
        }

        return $Builder->paginate($data['per_page']);
    }

    public function totalPoints()
    {
        $auth = Auth::user();
        $rows =  PointCustomer::select('customer_id', DB::raw('SUM(value) as total'))
            ->where('customer_id', $auth->id)->where('is_redeem', false)
            ->groupBy('customer_id')->get();

        if($rows->isEmpty()){
            return ['total' => 0];

        }

        $total = intval($rows[0]->total);
        return ['total' => $total];
    }

    public function exchangeToStamps($data)
    {
        $auth = Auth::user();
        $customer_id = $auth->id;
        $rows = PointCustomer::where('customer_id', $customer_id)->where('is_redeem', false)
            ->orderBy('created_at', 'asc')->get();
        $exchangePoints = $data['exchangePoints'];
        $stamps_num = $data['exchangePoints'] / Stamp::ONESTAMPVALUEPOINTS->value;
        $point_ids = [];
        $residue = new PointCustomer();
        foreach ($rows as $row) {

            if ($row->value <= $exchangePoints) {
                $exchangePoints -= $row->value;
                $point_ids[] = $row->id;
                continue;
            }

            if ($row->value > $exchangePoints) {
                $point_ids[] = $row->id;
                $residue_points = $row->value - $exchangePoints;
                $residue->value = $residue_points;
                $residue->customer_id = $row->customer_id;
                $residue->source ='點數兌換集章';
                $residue->source =$row->id;
                $exchangePoints=0;
            }

            if ($exchangePoints == 0) {
                break;
            }
        }


        if ($exchangePoints > 0) {
            throw new ErrException('點數無法兌換此數量集章');
        }

        DB::transaction(function () use ($point_ids, $stamps_num, $customer_id, $residue) {

            if ($residue->value > 0) {
                $residue->save();
            }
            PointCustomer::whereIn('id', $point_ids)->update(['is_redeem' => true]);
            $this->stampService->pointExchange(['stamps_num' => $stamps_num, 'customer_id' => $customer_id]);
        });
    }
}
