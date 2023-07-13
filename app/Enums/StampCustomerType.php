<?php

namespace App\Enums;

enum StampCustomerType:int
{
    case POINTSEXCHANGE = 1;  //點數兌換
    case SYSTEMCREATE =2;  //系統新增
    case STAY =3;   //住宿獲取

    case GIFT =4 ;  // 他人贈送
}
