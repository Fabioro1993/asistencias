<?php

namespace App\Providers;
//use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use LdapRecord\Laravel\Auth\BindFailureListener;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;
use App\Models\User;

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
    public function boot()
    {
        $this->registerPolicies();
        Fortify::authenticateUsing(function ($request) {

            $usuario = User::where('username', $request->username)->first();

            if ($usuario == null) {
                $validated = false;
            } else {
                
                $validated = Auth::validate([
                    'samaccountname' => $request->username,
                    'password' => $request->password
                ]);
            }
            
            return $validated ? Auth::getLastAttempted() : null;
        });
    }
}
