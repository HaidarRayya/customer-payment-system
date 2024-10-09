<?php

namespace App\Enums;

enum PaymentSearch: string
{
    case LASTEST  = "lastest";
    case OLDEST = "oldest";
    case HIGHEST  = "highest";
    case LOWEST = "lowest";
}
