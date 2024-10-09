<?php

namespace App\Enums;

enum OrderStatus: string
{
    case UNPAID  = "unpaid";
    case PAID = "paid";
    case ACCEPTED = "accepted";
    case REJECTED = "rejected";
}