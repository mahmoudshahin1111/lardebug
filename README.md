# LarDebug

[![](http://poser.pugx.org/lardebug/lardebug/v)](https://github.com/mahmoudshahin1111/lardebug/releases/tag/1.0.0)
[![Total Downloads](http://poser.pugx.org/lardebug/lardebug/downloads)](https://packagist.org/packages/lardebug/lardebug)
[![License](http://poser.pugx.org/lardebug/lardebug/license)](https://packagist.org/packages/lardebug/lardebug)

<!-- [![Latest Unstable Version](http://poser.pugx.org/lardebug/lardebug/v/unstable)](https://packagist.org/packages/lardebug/lardebug)  -->

## Installation

You can install the package through Composer.

```bash
composer require lardebug/lardebug
```

Then publish the config and migration file of the package using artisan.

in file `config\app.php` add this line to providers

```php
 'providers' => [
     /* */
     \LarDebug\ServiceProvider::class,
     /* */
 ]
```

## Usage

### Terminal
```bash
php artisan lardebug:serve
```
### In Browser
open http://localhost:3000
## Customize
```bash
php artisan vendor:publish --provider="LarDebug\ServiceProvider"
```

And adjust config file (`config/lardebug.php`) with your desired settings.

## License

The larabug package is open source software licensed under the [license MIT](http://opensource.org/licenses/MIT)
