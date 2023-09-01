<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case Canceled = 'canceled';
    case Challenge = 'challenge';
    case Expired = 'expired';
    case Failed = 'failed';
    case Pending = 'pending';
    case Success = 'success';
}
