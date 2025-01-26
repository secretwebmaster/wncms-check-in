<?php

namespace Wncms\CheckIn;

use Illuminate\Support\ServiceProvider;
use Wncms\CheckIn\Models\CheckIn;
use Wncms\Facades\MacroableModels;
use Wncms\Models\Credit;

class CheckInServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/wncms-check-in.php', 'wncms-check-in');
    }

    public function boot()
    {
        // Initialize the package
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/wncms-check-in.php' => config_path('wncms-check-in.php'),
                // __DIR__ . '/../migrations/' => database_path('migrations'),
            ], 'wncms-check-in');
        }

        // load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // load relationhips
        $this->bindRelationships();

        // load methods
        $this->bindMethods();
    }

    protected function bindRelationships()
    {
        // get the user model
        $userModel = config('wncms.default_user_model', \Wncms\Models\User::class);

        // bind relationship
        $userModel::resolveRelationUsing('check_ins', function ($user) {
            return $user->hasMany(CheckIn::class, 'user_id');
        });
    }

    protected function bindMethods()
    {
        // get the user model
        $userModel = config('wncms.default_user_model', \Wncms\Models\User::class);

        MacroableModels::addMacro($userModel, 'has_checked_in', function(){
            if(!auth()->check()) return false;
            $today = now()->startOfDay();
            return $this->check_ins()->whereDate('created_at', $today)->exists();
        });

        MacroableModels::addMacro($userModel, 'check_in_now', function(){

            if(!auth()->check()) return false;

            if(!$this->has_checked_in()){
                $checkIn = CheckIn::create(['user_id' => $this->id]);

                // Add credit
                // TODO allow user to set the credits to be added
                if (method_exists($this, 'credits') && true){
                    $amount = config('wncms-check-in.credits_amount_for_check_in', 1);
                    $type = config('wncms-check-in.credits_type_for_check_in', 'points');
                    Credit::add($this, $amount, $type);
                }

                return $checkIn;
            }

            return false;
        });
    }
}
