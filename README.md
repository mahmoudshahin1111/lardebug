#LarDebug

[![Latest Stable Version](http://poser.pugx.org/lardebug/lardebug/v)](https://packagist.org/packages/lardebug/lardebug) [![Total Downloads](http://poser.pugx.org/lardebug/lardebug/downloads)](https://packagist.org/packages/lardebug/lardebug) [![Latest Unstable Version](http://poser.pugx.org/lardebug/lardebug/v/unstable)](https://packagist.org/packages/lardebug/lardebug) [![License](http://poser.pugx.org/lardebug/lardebug/license)](https://packagist.org/packages/lardebug/lardebug)

## Installation on laravel

You can install the package through Composer.

```bash
composer require lardebug/lardebug
```

Then publish the config and migration file of the package using artisan.

```bash
php artisan vendor:publish --provider="LarDebug\ServiceProvider"
```

And adjust config file (`config/lardebug.php`) with your desired settings.
