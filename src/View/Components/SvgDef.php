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
	 * Return the svg element with the specified attributes
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return function (array $data) {
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

			return $doc->saveHTML($newSvgElement);
		};
	}
}
