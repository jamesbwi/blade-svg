# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jamesbwi/blade-svg.svg?style=flat-square)](https://packagist.org/packages/jamesbwi/blade-svg)
[![Total Downloads](https://img.shields.io/packagist/dt/jamesbwi/blade-svg.svg?style=flat-square)](https://packagist.org/packages/jamesbwi/blade-svg)

This package allows for easy use and manipulation of svg files within your laravel project.

## Installation

You can install the package via composer:

```bash
composer require jamesbwi/blade-svg
```

## Usage

To insert an SVG file, simply use the following component:
```html
<x-blade-svg src="img/apple.svg"/>
```
You can pass any attribute into the component as if it were an inline svg:
```html
<x-blade-svg src="img/apple.svg" class="apple-animation" viewBox="0 0 50 100"/>
```

### &lt;use&gt; tags
If you wish to utilise the SVG &lt;use&gt; tags this package simplifies the process. <br>
The following component will embed the SVG into an &lt;element&gt; tag with the specified attributes:

```html
<x-blade-svg-def id="apple" src="img/apple.svg" viewBox="0 0 50 100"/>
```

The element (or any other SVG with an id) can then be referenced with the following tag:

```html
<x-blade-svg-use href="#apple"/>
```

You can add any attribute to the &lt;use&gt; tag:

```html
<x-blade-svg-use href="#apple" class="apple-animation" width="500px" preserveAspectRatio="none"/>
```

External sources can be referenced too:

```html
<x-blade-svg-use href="fruit-bowl.svg#pear"/>
```


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email james@wearebwi.com instead of using the issue tracker.

## Credits

-   [James Smillie](https://github.com/jamesbwi)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
