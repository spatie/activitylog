# Log the activities of your users

[![License](https://poser.pugx.org/spatie/activitylog/license.png)](https://packagist.org/packages/spatie/activitylog)
[![Total Downloads](https://poser.pugx.org/spatie/activitylog/downloads.svg)](https://packagist.org/packages/spatie/activitylog)

This Laravel 5 package provides a very easy to use solution to log the activities of the users of your Laravel 5 app. All the activities will be logged in a db-table. Optionally the activities can also be logged against the default Laravel Log Handler.

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

Logging some activity is very simple.
```php

/* 
  The log-function takes two parameters:
  	- $text: the activity you wish to log.
  	- $user: optional can be an user id or a user object. 
  	         if not proved the id of Auth::user() will be used
  
*/
Activity::log('Some activity that you wish to log');
```

Over time your log will grow. To clean up the database table you can run this command:
```php
Activity::cleanLog();
```
By default records older than 2 months will be deleted. The number of months can be modified in the config-file of the package.
