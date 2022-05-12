<?php

namespace Jamesbwi\BladeSvg\View\Components;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class SvgDef extends Component
{
	/**
	 * @var string|false The svg file contents
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
	 * Generates a unique key for the svg to be used for caching
	 *
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
	 * Return the svg element with the specified attributes
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return function (array $data) {
			$cacheKey = $this->getCacheKey($data['attributes']->getAttributes());

			return Cache::remember($cacheKey, config('config.cache_duration'), function() use ($data) {
				$doc = new \DOMDocument();
				$doc->loadXML($this->svg);

				$oldSvgElement = $doc->getElementsByTagName('svg')->item(0);

				//create new svg with just a <symbol> tag
				$newSvgElement = $doc->createElement('svg');
				$newSvgElement->setAttribute('style', 'display: none');
				$symbol = $doc->createElement('symbol');
				$newSvgElement->appendChild($symbol);

				//add attributes to new <symbol>
				foreach ($oldSvgElement->attributes as $attribute) {
					$symbol->setAttribute($attribute->name, $attribute->value);
				}
				foreach ($data['attributes'] as $key => $value) {
					$symbol->setAttribute($key, $value);
				}

				//move child elements to new <symbol>
				$children = [];
				foreach ($oldSvgElement->childNodes as $node) {
					if ($node->nodeType === 1) {
						$children[] = $node;
					}
				}
				foreach ($children as $node) {
					$symbol->appendChild($node);

					$node->removeAttributeNS('http://www.w3.org/2000/svg', 'default');
				}

				return $doc->saveHTML($newSvgElement); //preg_replace('/(<\/|<)[a-zA-Z]+:([a-zA-Z0-9]+[ =>])/', '$1$2', $doc->saveHTML($svgElement));
			});
		};
	}
}
