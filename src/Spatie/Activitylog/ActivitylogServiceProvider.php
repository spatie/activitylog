<?php namespace Spatie\Activitylog;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ActivitylogServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Publish a config file
		$this->publishes([
			__DIR__.'/../../config/activitylog.php' => config_path('activitylog.php')
		], 'config');

// Publish your migrations
		$this->publishes([
			__DIR__.'/../../migrations/' => base_path('/database/migrations')
		], 'migrations');

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'activity',
			'Spatie\Activitylog\ActivitylogSupervisor'
		);

		$this->app->bind(
			'Spatie\Activitylog\Handlers\ActivitylogHandler',
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

}
