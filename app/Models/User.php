<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'points'
    ];
    protected $guarded = [
        'password',
        'role',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function customer_transfers()
    {
        return $this->hasMany(Transfer::class, 'customer_id');
    }
    public function pointRelitier_transfers()
    {
        return $this->hasMany(Transfer::class, 'pointRelitier_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'customer_id');
    }

    public function lastetPayment()
    {

        return $this->hasOne(Payment::class, 'customer_id')
            ->latestOfMany('created_at');
    }


    public function oldestPayment()
    {
        return $this->hasOne(Payment::class, 'customer_id')
            ->oldestOfMany('created_at');
    }



    public function highestPayment()
    {

        return $this->hasOne(Payment::class, 'customer_id')
            ->OfMany('amount', 'MAX');
    }


    public function lowestPayment()
    {
        return $this->hasOne(Payment::class, 'customer_id')
            ->OfMany('amount', "MIN");
    }


    /**
     *  search a user by name
     * @param  Builder $query  
     * @param  string $name  
     * @return Builder query  
     */
    public function scopeByUserName(Builder $query, $name)
    {
        if ($name != null)
            return $query->where('name', 'like', "%$name%");
        else
            return $query;
    }


    /**
     *  search a user by role
     * @param  Builder $query  
     * @param  string $operation  
     * @param  string $role  
     * @return Builder query  
     */
    public function scopeByRole(Builder $query, $operation, $role)
    {
        if ($role != null)
            return $query->where('name', $operation, $role);
        else
            return $query;
    }
    /**
     *  search a user by email
     * @param  Builder $query  
     * @param  string $email  
     * @return Builder query  
     */
    public function scopeByEmail(Builder $query, $email)
    {
        return $query->where('email', '=', $email);
    }
}