<?php

namespace App\Enums;

enum PotintCustomerTye:int
{
    case EXCHANGE_STAMP = 1;
    case STORE_SCAN = 2;
    case CONSUME =3;
    case SYSTEM_CREATE =4;

    case SYSTEM_DELETE =5;
}
