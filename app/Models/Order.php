<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'price',
        'customer_id'
    ];
    protected $guarded = [
        'status',
    ];

    protected $attributes = [
        'status' => OrderStatus::UNPAID->value,
    ];
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function order_details()
    {
        return $this->hasMany(OrderDetails::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
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