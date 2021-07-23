# LarDebug

[![](http://poser.pugx.org/lardebug/lardebug/v)](https://github.com/mahmoudshahin1111/lardebug/releases/tag/1.1.0)
[![License](http://poser.pugx.org/lardebug/lardebug/license)](https://packagist.org/packages/lardebug/lardebug)
<!-- [![Total Downloads](http://poser.pugx.org/lardebug/lardebug/downloads)](https://packagist.org/packages/lardebug/lardebug) -->
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
In "lardebug.php" file you can customize server configurations
```php
return [
    'server' => [
        'host' => 'localhost',
        'port' => 3000, // Change to any port as you want 👍
    ],
];
```
### Send message to server immediately with Facade
```php
    LarDebug::log("message");
```
also he works fine with laravel apis
### add messages to request
```php
    Log::debug("message");
```


And adjust config file (`config/lardebug.php`) with your desired settings.

## License

The larabug package is open source software licensed under the [license MIT](http://opensource.org/licenses/MIT)
