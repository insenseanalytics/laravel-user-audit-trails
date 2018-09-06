<?php

namespace Insense\LaravelUserAuditTrails;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class UserTrailsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        Blueprint::macro('usertrails', function ($created_by = 'created_by', $updated_by = 'updated_by') {
            if (!is_null($created_by)) {
                $this->integer($created_by)->unsigned()->nullable();
            }

            if (!is_null($updated_by)) {
                $this->integer($updated_by)->unsigned()->nullable();
            }
            return $this;
        });
        
        Blueprint::macro('deletetrails', function ($deleted_by = 'deleted_by') {
            if (!is_null($deleted_by)) {
                $this->integer($deleted_by)->unsigned()->nullable();
            }
            return $this;
        });

        Blueprint::macro('dropUsertrails', function ($created_by = 'created_by', $updated_by = 'updated_by') {
            $columnsToDrop = [];
            if (!is_null($created_by)) {
                $columnsToDrop[] = $created_by;
            }

            if (!is_null($updated_by)) {
                $columnsToDrop[] = $updated_by;
            }
            
            if (!empty($columnsToDrop)) {
                $this->dropColumn($columnsToDrop);
            }
        });
        
        Blueprint::macro('dropDeletetrails', function ($deleted_by = 'deleted_by') {
            $columnsToDrop = [];
            if (!is_null($deleted_by)) {
                $columnsToDrop[] = $deleted_by;
            }
            
            if (!empty($columnsToDrop)) {
                $this->dropColumn($columnsToDrop);
            }
        });
    }
}
