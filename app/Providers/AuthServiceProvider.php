<?php

namespace App\Providers;

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
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('manage', function ($user) {
            return $user->hasRole('teacher');
        });

        $gate->define('delete-discussion', function ($user, $discuss) {
            return $user->id === $discuss->user_id;
        });

        $gate->define('member-of', function($user, $classroom) {
            if($user->id == $classroom->teacher_id || $classroom->isMember($user->id)) {
                return true;
            }
        });
    }
}
