<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'customer_id',
        'pointRelitier_id',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'amount' => 'double',
    ];
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function pointRelitier()
    {
        return $this->belongsTo(User::class, 'pointRelitier_id');
    }

    /**
     *  search a user by amount
     * @param  Builder $query  
     * @param  string $amount  
     * @return Builder query  
     */
    public function scopeByAmount(Builder $query, $amount)
    {
        if ($amount != null)
            return $query->where('amount', '=', $amount);
        else
            return $query;
    }


    /**
     *  search a user by date
     * @param  Builder $query  
     * @param  string $date  
     * @return Builder query  
     */
    public function scopeByDate(Builder $query, $date)
    {
        if ($date != null)
            return $query->where('created_at', '=', $date);
        else
            return $query;
    }

    /**
     *  sort by created_at
     * @param  Builder $query  
     * @param  string $sort  
     * @return Builder query  
     */
    public function scopeBySort(Builder $query, $sort)
    {
        if ($sort != null)
            return $query->orderBy('created_at', $sort);
        else
            return $query->orderByDesc('created_at');
    }
}
