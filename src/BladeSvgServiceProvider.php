<?php

namespace Jamesbwi\BladeSvg;

use Illuminate\Support\ServiceProvider;
use Jamesbwi\BladeSvg\View\Components\SvgUse;
use Jamesbwi\BladeSvg\View\Components\SvgDef;
use Jamesbwi\BladeSvg\View\Components\Svg;

class BladeSvgServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 */
	public function boot()
	{
		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('blade-svg.php'),
			], 'blade-svg');
		}

		$this->loadViewComponentsAs('blade', [
			/**
			 * Form
			 */
			Svg::class,
			SvgUse::class,
			SvgDef::class,
		]);
	}

	public function register()
	{
		$this->mergeConfigFrom(__DIR__ .'/../config/config.php', 'blade-svg');
	}
}
