<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN  = "admin";
    case POINT_RELITIER  = "point reliter";
    case CUSTOMER  = "customer";
}
