<?php

namespace Hatamiarash7\Nazer;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as Provider;

/**
 * Service provider for the exception handler.
 *
 * @author    Arash Hatami
 */
class ServiceProvider extends Provider
{
	/**
	 * Add an alias for the exception handler facade.
	 *
	 * @return    void
	 */
	public function boot()
	{
		AliasLoader::getInstance()->alias('Exceptions', Exceptions::class);

		$configPath = __DIR__ . '/../config/nazer.php';
		if (function_exists('config_path')) {
			$publishPath = config_path('nazer.php');
		} else {
			$publishPath = base_path('config/nazer.php');
		}
		$this->publishes([$configPath => $publishPath], 'config');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->aliasExceptionHandler();
		$this->registerExceptionHandlersRepository();
		$this->extendExceptionHandler();
	}

	/**
	 * Create an alias for the Laravel default exception handler.
	 *
	 * @return    void
	 */
	private function aliasExceptionHandler()
	{
		$this->app->alias(ExceptionHandler::class, 'exceptions');
	}

	/**
	 * Register the custom exception handlers repository.
	 *
	 * @return    void
	 */
	private function registerExceptionHandlersRepository()
	{
		$this->app->singleton('exceptions.repository', Repository::class);
	}

	/**
	 * Extend the Laravel default exception handler.
	 *
	 * @return    void
	 */
	private function extendExceptionHandler()
	{
		$this->app->extend(ExceptionHandler::class, function ($handler, $app) {
			return new Decorator($handler, $app['exceptions.repository']);
		});
	}
}