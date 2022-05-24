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
	 * Create a new component instance.
	 *
	 * @param string $src
	 *
	 */
	public function __construct(string $src)
	{
		$this->svg = file_get_contents($src);
	}

	/**
	 * Return the svg with the specified attributes
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return function (array $data) {
			$doc = new \DOMDocument();
			$doc->loadXML($this->svg);

			$element = $doc->getElementsByTagName('svg')->item(0);

			foreach($data['attributes'] as $key => $value) {
				$element->setAttribute($key, $value);
			}

			return $doc->saveHTML($element);
		};
	}
}
