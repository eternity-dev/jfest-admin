<?php

namespace App\Enums;

enum AttendStatusEnum: string
{
    case Attended = 'attended';
    case NotAttended = 'not-attended';
}
