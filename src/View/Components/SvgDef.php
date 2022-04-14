<?php

namespace Jamesbwi\BladeSvg\View\Components;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\Component;

use function view;

class SvgDef extends Component
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
	 * @var string
	 */
	public $id;

	/**
	 * Create a new component instance.
	 *
	 * @param string $src
	 * @param string $id
	 *
	 */
	public function __construct(string $src, string $id)
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

		$cacheKey = 'svg-def-' . $this->path;
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

			return Cache::remember($cacheKey, $minutes = config('config.cache_duration'), function() use ($data) {
				$doc = new \DOMDocument();
				$doc->loadXML($this->svg);

				$element = $doc->getElementsByTagName('svg')->item(0);

				$newDoc = new \DOMDocument();

				$svgElement = $newDoc->createElement('svg');
				$newDoc->appendChild($svgElement);
				$svgElement->setAttribute('style', 'display: none');

				$symbol = $newDoc->createElement('symbol');
				$svgElement->appendChild($symbol);

				foreach ($element->attributes as $attribute) {
					$symbol->setAttribute($attribute->name, $attribute->value);
				}
				foreach ($data['attributes'] as $key => $value) {
					$symbol->setAttribute($key, $value);
				}

				$symbol->setAttribute('id', $this->id);

				foreach ($element->childNodes as $node) {
					if ($node->nodeType === 1) {
						$import = $newDoc->importNode($node, true);

						$symbol->appendChild($import);
					}
				}

				return $this->stripString($newDoc->saveHTML(), '<svg', '</svg>') . '</svg>';
			});
		};
	}
}
