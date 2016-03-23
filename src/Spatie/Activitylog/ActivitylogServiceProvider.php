<?php

namespace Spatie\Activitylog;

use Illuminate\Support\ServiceProvider;

class ActivitylogServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        // Publish a config file
        $this->publishes([
            __DIR__ . '/../../config/activitylog.php' => config_path('activitylog.php'),
        ], 'config');

        if (!$this->migrationHasAlreadyBeenPublished()) {
            // Publish migration
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . "/../../migrations/create_activity_log_table.stub" => database_path("/migrations/{$timestamp}_create_activity_log_table.php"),
            ], 'migrations');
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(
            'activity',
            'Spatie\Activitylog\ActivitylogSupervisor'
        );

        $this->app->bind(
            'Spatie\Activitylog\Handlers\ActivitylogHandlerInterface',
            'Spatie\Activitylog\Handlers\EloquentHandler'
        );
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
     * @return bool
     */
    protected function migrationHasAlreadyBeenPublished()
    {
        $files = glob(database_path('/migrations/*_create_activity_log_table.php'));

        return count($files) > 0;
    }
}
