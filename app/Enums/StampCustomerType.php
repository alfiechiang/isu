<?php

namespace App\Enums;

enum StampCustomerType:int
{
    case POINTSEXCHANGE = 1;  //點數兌換
    case STAY =2;  //住宿獲取
    case SOMEONE_DELIVER =3;   //他人贈送

    case ALREADY_USE =4;   //已使用

    case HAVE_EXPIRE =5;   //已過期

    case ALREADY_USE_SOMEONE_DELIVER =6;   //已使用（他人贈送）

    case ALREADY_USE_OWN_DELIVER =7;   //已使用（他人贈送）

    case HAVE_EXPIRE_SOMEONE_DELIVER =8;   //已過期

    case SYSTEM_SEND =9 ;  // 系統發放
}

