<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'category_id',
        'count',
        'price'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'price' => 'double',
        'count' => 'int',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * search products by  name
     * @param  Builder $query  
     * @param  bool $name  
     * @return Builder query  
     */
    public function scopeByName(Builder $query, $name)
    {
        if ($name != null)
            return $query->where('name', 'like', "%$name%");
        else
            return $query;
    }


    /**
     * search products by  is_active
     * @param  Builder $query  
     * @param  bool $is_active  
     * @return Builder query  
     */
    public function scopeByIsActive(Builder $query, $is_active)
    {
        if ($is_active != null)
            return $query->where('count', '>', 0);
        else
            return $query;
    }
}