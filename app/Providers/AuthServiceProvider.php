<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot( GateContract $gate)
    {
        $this->registerPolicies();

        $gate->define('isAdmin', function( $user ){
            // foreach ($user->user_status as $role) {
            //     // dd($user->user_status);
            //      return $role->role == '1';
            // }
            if ($user->user_status == '1') {
                return true;
            }
        });

        // Gate::define('admin')
    }
}
