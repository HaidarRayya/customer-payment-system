<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'order_id',
        'customer_id'
    ];
    protected $guarded = [
        'status',
    ];

    protected $attributes = [
        'status' => PaymentStatus::UNPAID->value,
    ];
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function order()
    {
        return $this->belongsTo(User::class, 'order_id');
    }
    public function scopeMyPayment(Builder $query, $customer_id, $order_id)
    {
        return $query->where('customer_id', '=', $customer_id)->where('order_id', '=', $order_id);
    }

    /**
     * search order by  status
     * @param  Builder $query  
     * @param  bool $status  
     * @return Builder query  
     */
    public function scopeByStatus(Builder $query, $status)
    {
        if ($status != null)
            return $query->where('status', '=', $status);
        else
            return $query;
    }
}