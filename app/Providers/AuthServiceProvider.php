<?php

namespace App\Providers;

use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('update-cart', function ($user, $cart) {
            return $user->id == $cart->customer_id ? ''
                : Response::deny('لا يمكنك القيام بهذه العملية');
        });
        Gate::define('delete-cart', function ($user, $cart) {
            return $user->id == $cart->customer_id ?
                ''
                : Response::deny('لا يمكنك القيام بهذه العملية');
        });


        Gate::define('start-order', function ($user, $order) {
            return $user->id == $order->delivery_id ?
                Response::allow()
                : Response::deny('لا يمكنك القيام بهذه العملية');
        });

        Gate::define('end-order', function ($user, $order) {
            return $user->id == $order->delivery_id ?
                Response::allow()
                : Response::deny('لا يمكنك القيام بهذه العملية');
        });

        Gate::define('delete-order', function ($user, $order) {
            return $user->id == $order->customer_id ?
                Response::allow()
                : Response::deny('لا يمكنك القيام بهذه العملية');
        });
    }
}
