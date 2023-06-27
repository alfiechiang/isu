<?php

namespace App\Enums;

enum CustomerStatus:string
{
    case DISABLED = 'disabled';
    case ENABLED = 'enabled';
    case UNVERIFIED = 'unverified';
}
