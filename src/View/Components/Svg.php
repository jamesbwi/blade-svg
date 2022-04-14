<?php

namespace Jamesbwi\BladeSvg\View\Components;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Svg extends Component
{
	/**
	 * @var string|false
	 */
	public $svg;

	/**
	 * @var string
	 */
	public $path;

	/**
	 * @var string|null
	 */
	public $id;


	/**
	 * Create a new component instance.
	 *
	 * @param string $src
	 * @param string|null $id
	 *
	 */
	public function __construct(string $src, string $id = null)
	{
		$this->path = $src;

		$this->svg = file_get_contents($src);

		$this->id = $id;
	}

	/**
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
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return function (array $data) {
			$cacheKey = $this->getCacheKey($data['attributes']->getAttributes());

			return Cache::remember($cacheKey, $seconds = config('config.cache_duration'), function() use ($data) {
				$doc = new \DOMDocument();
				$doc->loadXML($this->svg);

				$element = $doc->getElementsByTagName('svg')->item(0);

				if($this->id) {
					$element->setAttribute('id', $this->id);
				}

				foreach($data['attributes'] as $key => $value) {
					$element->setAttribute($key, $value);
				}

				return $this->stripString($doc->saveHTML(), '<svg', '</svg>') . '</svg>';
			});
		};
	}
}
