<?php

namespace Jamesbwi\BladeSvg\View\Components;

use Illuminate\View\Component;

use function view;

class SvgUse extends Component
{

	/**
	 * @var string|null the id of the svg being used
	 */
	public $href;

	/**
	 * Create a new component instance.
	 *
	 * @param string|null $href
	 *
	 */
	public function __construct(string $href = null)
	{
		$this->href = str_contains($href, '#') ? $href : '#' . $href;
	}

	/**
	 * Generates substring between a start point and end point
	 *
	 * @param $string
	 * @param $start
	 * @param $end
	 * @return string
	 */
	function stripString($string, $start, $end) {
		$stripped = substr($string, strpos($string, $start));

		return substr($stripped, 0, strpos($stripped, $end));
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
			$svgElement = $doc->createElement('svg');

			$element = $doc->createElement('use');

			$element->setAttribute('href', $this->href);

			foreach($data['attributes'] as $key => $value) {
				$svgElement->setAttribute($key, $value);
			}

			$svgElement->appendChild($element);
			$doc->appendChild($svgElement);

			return $this->stripString($doc->saveXML(), '<svg', '</svg>') . '</svg>';
		};
	}
}
