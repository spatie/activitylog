# Log the activity of your users

[![Latest Version](https://img.shields.io/github/release/spatie/activitylog.svg?style=flat-square)](https://github.com/spatie/activitylog/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/activitylog/master.svg?style=flat-square)](https://travis-ci.org/spatie/activitylog)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/c48809c7-cdb3-4e86-974b-ad9c6282bc3c.svg)](https://insight.sensiolabs.com/projects/c48809c7-cdb3-4e86-974b-ad9c6282bc3c)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/activitylog.svg?style=flat-square)](https://packagist.org/packages/spatie/activitylog)

## EOL-warning

This package has been abandoned on 2016-07-28. Please use [laravel-activitylog](https://github.com/spatie/laravel-activitylog) instead.

## Description

This Laravel 5 package provides a very easy to use solution to log the activities of the users of your Laravel 5 app. All the activities will be logged in a db-table. Optionally the activities can also be logged against the default Laravel Log Handler.

Spatie is a webdesign agency in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

### Note:

If you're using Laravel 4, take a look at version 0.3.0 of this package.

## Installation

This package can be installed through Composer.
```bash
composer require spatie/activitylog
```


This service provider must be registered.
```php
// config/app.php

'providers' => [
    '...',
    'Spatie\Activitylog\ActivitylogServiceProvider',
];
```


You'll also need to publish and run the migration in order to create the db-table.
```
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="migrations"
php artisan migrate
```


Activitylog also comes with a facade, which provides an easy way to call it.
```php
// config/app.php

'aliases' => [
	...
	'Activity' => 'Spatie\Activitylog\ActivitylogFacade',
];
```


Optionally you can publish the config file of this package.
```
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="config"
```
The configuration will be written to  ```config/activitylog.php```. The options provided are self explanatory.


## Usage

### Manual logging

Logging some activity is very simple.
```php
//at the top of your file you should import the facade.
use Activity;
...
/*
  The log-function takes two parameters:
  	- $text: the activity you wish to log.
  	- $user: optional can be an user id or a user object.
  	         if not proved the id of Auth::user() will be used

*/
Activity::log('Some activity that you wish to log');
```
The string you pass to function gets written in a db-table together with a timestamp, the ip address and the user agent of the user.

### Log model events
This package can log the events from your models. To do so your model must use the `LogsActivity`-trait and implement `LogsActivityInterface`.

```php
use Spatie\Activitylog\LogsActivityInterface;
use Spatie\Activitylog\LogsActivity;

class Article implements LogsActivityInterface {

   use LogsActivity;
...
```

The interface expects you to implement the `getActivityDescriptionForEvent`-function.

Here's an example of a possible implementation.

```php
/**
 * Get the message that needs to be logged for the given event name.
 *
 * @param string $eventName
 * @return string
 */
public function getActivityDescriptionForEvent($eventName)
{
    if ($eventName == 'created')
    {
        return 'Article "' . $this->name . '" was created';
    }

    if ($eventName == 'updated')
    {
        return 'Article "' . $this->name . '" was updated';
    }

    if ($eventName == 'deleted')
    {
        return 'Article "' . $this->name . '" was deleted';
    }

    return '';
}
```
The result of this function will be logged, unless the result is an empty string.

### Using a before handler.
If you want to disable logging under certain conditions,
such as for a specific user, create a class in your application
namespace that implements the `Spatie\Activitylog\Handlers\BeforeHandlerInterface`.

This  interface defines an `shouldLog()` method in which you can code any custom logic to determine
whether logging should be ignored or not. You must return `true` the call should be logged.

To en the namespaced class nameto the `beforeHandler` field in the configuration file:
```php
'beforeHandler' => '\App\Handlers\BeforeHandler',
```

For example, this callback class could look like this to disable
logging a user with id of 1:
```php
<?php

namespace App\Handlers;

use Spatie\Activitylog\Handlers\BeforeHandlerInterface;

class BeforeHandler implements BeforeHandlerInterface
{
    public function shouldLog($text, $userId)
	{
		if ($userId == 1) return false;

		return true;
	}
}
```

### Retrieving logged entries
All events will be logged in the `activity_log`-table. This package provides an Eloquent model to work with the table. You can use all the normal Eloquent methods that you know and love. Here's how you can get the last 100 activities together with the associated users.

```php
use Spatie\Activitylog\Models\Activity;

$latestActivities = Activity::with('user')->latest()->limit(100)->get();
```

### Cleaning up the log

Over time your log will grow. To clean up the database table you can run this command:
```php
Activity::cleanLog();
```
By default records older than 2 months will be deleted. The number of months can be modified in the config-file of the package.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
