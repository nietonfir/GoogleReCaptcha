GoogleReCaptcha
===============

[ReCAPTCHA](https://developers.google.com/recaptcha/) is a free CAPTCHA service that protect websites from spam and abuse.
This library aims to providate an alternative to the [official ReCAPTCHA library](https://github.com/google/ReCAPTCHA) for verifying a users "No CAPTCHA reCAPTCHA" response.
Internally it uses [Guzzle](https://github.com/guzzle/guzzle) for communicating with the ReCAPTCHA API.

[![Build Status](https://travis-ci.org/nietonfir/GoogleReCaptcha.svg?branch=master)](https://travis-ci.org/nietonfir/GoogleReCaptcha) [![Latest Stable Version](https://poser.pugx.org/nietonfir/google-recaptcha/v/stable.svg)](https://packagist.org/packages/nietonfir/google-recaptcha) [![Latest Unstable Version](https://poser.pugx.org/nietonfir/google-recaptcha/v/unstable.svg)](https://packagist.org/packages/nietonfir/google-recaptcha) [![License](https://poser.pugx.org/nietonfir/google-recaptcha/license.svg)](https://packagist.org/packages/nietonfir/google-recaptcha)

Installation
------------

The recommended way to install GoogleReCaptcha is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of GoogleReCaptcha:

```bash
composer require "nietonfir/google-recaptcha"
```

Or add GoogleReCaptcha in your `composer.json`

```js
"require": {
    "nietonfir/google-recaptcha": "~0.0"
}
```

and tell Composer to install the library:

``` bash
composer update "nietonfir/google-recaptcha"
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

Usage
-----

A sample validation client could look like the following:

```php
use GuzzleHttp\Client;
use Nietonfir\Google\ReCaptcha\ReCaptcha;
use Nietonfir\Google\ReCaptcha\Api\RequestData,
    Nietonfir\Google\ReCaptcha\Api\ResponseFactory;

$requestData = new RequestData(
    'YOUR_API_SECRET_HERE',         // secret
    $_POST['g-recaptcha-response'], // user response
    $_SERVER['REMOTE_ADDR']         // end user IP
);

$reCaptcha = new ReCaptcha(new Client(), new ResponseFactory());
$response = $reCaptcha->processRequest($requestData);

if ($response->isValid()) {
    echo 'I\'m not a robot';
} else {
    var_dump($response->getErrors());
}
```
