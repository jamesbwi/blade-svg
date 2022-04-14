<?php

namespace Jamesbwi\BladeSvg\Tests;

use \Orchestra\Testbench\TestCase;
use Jamesbwi\BladeSvg\View\Components\Svg;
use Jamesbwi\BladeSvg\View\Components\SvgUse;
use Jamesbwi\BladeSvg\View\Components\SvgDef;
use Jamesbwi\BladeSvg\BladeSvgServiceProvider;


class Test extends TestCase
{

	public function setUp(): void
	{
		parent::setUp();

		$this->blade = app('blade.compiler');
	}

	/**
	 * Get package providers.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 *
	 * @return array
	 */
	protected function getPackageProviders($app)
	{
		return [
			BladeSvgServiceProvider::class,
		];
	}

	private function removeWhiteSpace($string) {
		return trim(preg_replace('/&gt;(\s)+&lt;/m', '&gt;&lt;', $string));
	}

	/** @test */
	public function callNoAttribute()
	{
		$image = __DIR__ . '/images/circle.svg';

		$svg = new Svg($image);

		$view = $svg->render();

		dd($view);

		$this->assertStringStartsWith('<svg ', $view);
		$this->assertStringEndsWith('</svg>', $view);
		$this->assertStringContainsString('viewBox="0 0 100 100"', $view);
		$this->assertStringContainsString('<circle ', $view);
	}

	/** @test */
	public function addingAttributes()
	{
		$image = __DIR__ . '/images/circle.svg';

		$svg = new Svg($image, [
			'width' => '300px',
			'height' => '300px',
		]);

		$view = $this->blade->compileString($svg->render());

		$this->assertStringContainsString(htmlspecialchars('width="300px"'), $view);
		$this->assertStringContainsString(htmlspecialchars('height="300px"'), $view);
	}

	/** @test */
	public function replaceAttribute()
	{
		$image = __DIR__ . '/images/circle.svg';

		$svg = new Svg($image, [
			'viewBox' => '0 0 50 50',
		]);

		$view = $this->blade->compileString($svg->render());

		$this->assertStringContainsString(htmlspecialchars('viewBox="0 0 50 50"'), $view);
		$this->assertStringNotContainsString(htmlspecialchars('viewBox="0 0 100 100"'), $view);
	}

	/** @test */
	public function useSvg()
	{
		$image = __DIR__ . '/images/circle.svg';

		$svg = new SvgDef($image, 'testSvg');

		$view = $this->blade->compileString($svg->render());

		$this->assertStringContainsString(htmlspecialchars('viewBox="0 0 50 50"'), $view);
		$this->assertStringNotContainsString(htmlspecialchars('viewBox="0 0 100 100"'), $view);
	}

	/** @test */
	public function bladeComponent()
	{
		$image = __DIR__ . '/images/circle.svg';

		$result = $this->blade->compileString('<x-blade-svg-svg></x-blade-svg-svg>');

		dd($result);
	}
}
