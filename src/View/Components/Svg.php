<?php

namespace Jamesbwi\BladeSvg\View\Components;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Svg extends Component
{
	/**
	 * @var string|false the svg file contents
	 *
	 */
	public $svg;

	/**
	 * @var string The file path of the svg
	 */
	public $path;

	/**
	 * Create a new component instance.
	 *
	 * @param string $src
	 *
	 */
	public function __construct(string $src)
	{
		$this->path = $src;

		$this->svg = file_get_contents($src);
	}

	/**
	 * Generates substring between a start point and end point
	 *
	 * @param $string
	 * @param $start
	 * @param $end
	 * @return string
	 */
	protected function stripString($string, $start, $end) {
		$stripped = substr($string, strpos($string, $start));

		return substr($stripped, 0, strpos($stripped, $end));
	}

	/**
	 * Generates a unique key for the svg to be used for caching
	 *
	 * @param array $attributes
	 * @return string
	 */
	protected function getCacheKey(array $attributes) {
		ksort($attributes);

		$cacheKey = $this->path;

		foreach ($attributes as $key => $value) {
			$cacheKey .= '#' . $key . '=' . Str::slug($value, '-');
		}

		return $cacheKey;
	}

	/**
	 * Return the svg with the specified attributes
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return function (array $data) {
			$cacheKey = $this->getCacheKey($data['attributes']->getAttributes());

			return Cache::remember($cacheKey, $days = config('config.cache_duration'), function() use ($data) {
				$doc = new \DOMDocument();
				$doc->loadXML($this->svg);

				$element = $doc->getElementsByTagName('svg')->item(0);

				foreach($data['attributes'] as $key => $value) {
					$element->setAttribute($key, $value);
				}

				return $this->stripString($doc->saveHTML(), '<svg', '</svg>') . '</svg>';
			});
		};
	}
}
