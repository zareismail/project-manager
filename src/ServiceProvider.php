<?php

namespace Zareismail\ProjectManager;

use Illuminate\Support\Facades\Gate; 
use Illuminate\Contracts\Support\DeferrableProvider; 
use Illuminate\Support\ServiceProvider as LaravelServiceProvider; 
use Laravel\Nova\Nova as LaravelNova; 
use Zareismail\Task\Models\Task;

class ServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{ 
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['config']->set('nova.currency', 'IRR');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations'); 
        
        Gate::policy(Models\Leave::class, Policies\Leave::class);
        Gate::policy(Models\Project::class, Policies\Project::class);
        Gate::policy(Models\Document::class, Policies\Document::class);
        Gate::policy(Models\Employer::class, Policies\Employer::class);
        Gate::policy(Models\Material::class, Policies\Material::class);
        Gate::policy(Models\Inventory::class, Policies\Inventory::class);

        LaravelNova::resources([ 
            Nova\Material::class,
            Nova\Employer::class,
            Nova\Document::class,
            Nova\Leave::class,
            Nova\Project::class,
            Nova\Inventory::class,
            Nova\InventoryMaterial::class,
        ]);

        Task::saved(function($model) { 
            if ($model->taskable_type !== Models\Leave::class) {
                return;
            }

            if (! ($model->isCompleted() || $model->isRejected())) {
                return;
            }

            if ($leave = Models\Leave::pendings()->find($model->taskable_id)) {
                $model->isCompleted() ? $leave->accept() : $leave->reject();
            } 
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [
            \Laravel\Nova\Events\ServingNova::class,
            \Illuminate\Console\Events\ArtisanStarting::class,
        ];
    }
}
