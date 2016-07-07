<?php
/**
 * Created by PhpStorm.
 * User: anik
 * Date: 7/7/16
 * Time: 2:09 PM
 */

namespace Anik\Generator\Providers;

use Anik\Generator\RepositoryGeneratorCommand;
use Illuminate\Support\ServiceProvider;

class RepositoryGeneratorServiceProvider extends ServiceProvider
{
	protected $commands = [
		RepositoryGeneratorCommand::class,
	];

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register ()
	{
		$this->registerRepositoryGeneratorCommand();
		$this->publishes([
			__DIR__ . '/../config/generator.php' => config_path('generator.php'),
		]);

	}

	private function registerRepositoryGeneratorCommand ()
	{
		$this->commands($this->commands);
	}

	public function boot ()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/generator.php', 'generator');
	}
}