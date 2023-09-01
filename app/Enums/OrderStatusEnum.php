<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case Expired = 'expired';
    case Paid = 'paid';
    case Pending = 'pending';
}
