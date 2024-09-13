<?php

namespace App\Enums;

enum StatusEnum: string
{
    case APPROVED = 'APPROVED';
    case WAITING = 'WAITING';
    case DECLINED = 'DECLINED';
    case ERROR = 'ERROR';
}
