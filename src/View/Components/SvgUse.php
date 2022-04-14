<?php

namespace Jamesbwi\BladeSvg\View\Components;

use Illuminate\View\Component;

use function view;

class SvgUse extends Component
{

	public $id;

	/**
	 * Create a new component instance.
	 *
	 * @param string|null $id
	 *
	 */
	public function __construct(string $id = null)
	{
		$this->id = $id;
	}

	function stripString($string, $start, $end) {
		$stripped = substr($string, strpos($string, $start));

		return substr($stripped, 0, strpos($stripped, $end));
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return function (array $data) {
			$doc = new \DOMDocument();
			$svgElement = $doc->createElement('svg');

			$element = $doc->createElement('use');

			$element->setAttribute('href', '#' . $this->id);

			foreach($data['attributes'] as $key => $value) {
				$svgElement->setAttribute($key, $value);
			}

			$svgElement->appendChild($element);
			$doc->appendChild($svgElement);

			return $this->stripString($doc->saveXML(), '<svg', '</svg>') . '</svg>';
		};
	}
}
