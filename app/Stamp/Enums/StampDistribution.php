<?php

namespace App\Stamp\Enums;

use App\Point\PointService;
use App\Stamp\Distribution\DistributionStrategy;
use App\Stamp\Distribution\PointExchangeStrategy;
use App\Stamp\Distribution\StoreDistributionStrategy;
use App\Traits\Enum;

enum StampDistribution:string
{
    case POINT = 'point';
    case STORE = 'store';

    public function create($stampCard): DistributionStrategy
    {
        switch ($this) {
            case self::STORE:
                return new StoreDistributionStrategy($stampCard);
            case self::POINT:
                return new PointExchangeStrategy($stampCard, new PointService());
        }
    }
}
