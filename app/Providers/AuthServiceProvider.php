<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use App\Models\User;
use Carbon\Carbon;

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
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        \Laravel\Passport\Passport::tokensExpireIn(Carbon::now()->addDays(7));
        \Laravel\Passport\Passport::personalAccessTokensExpireIn(Carbon::now()->addDays(7));
        \Laravel\Passport\Passport::refreshTokensExpireIn(Carbon::now()->addMonth());
        // Passport::loadKeysFrom(__DIR__.'/../secrets/oauth');

        // $data = User::select('username')->get()->map(function($val){
        //     return $val['username'];
        // });
    
        // $tokensCan = [];
        // $scope = [];
    
        // foreach($data as $key => $val)
        // {
        //     $scope_key = strtolower(str_replace(' ','-',collect($val)->first()));
        //     $tokensCan[$scope_key] = collect($val)->first();
        //     $scope[] = $scope_key;
        // }

        // Passport::tokensCan($tokensCan);
         
        // Passport::setDefaultScope($scope);
    }
}
