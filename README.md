# Log the activity of your users

[![Latest Version](https://img.shields.io/github/release/spatie/activitylog.svg?style=flat-square)](https://github.com/spatie/activitylog/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/activitylog/master.svg?style=flat-square)](https://travis-ci.org/spatie/activitylog)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/c48809c7-cdb3-4e86-974b-ad9c6282bc3c.svg)](https://insight.sensiolabs.com/projects/c48809c7-cdb3-4e86-974b-ad9c6282bc3c)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/activitylog.svg?style=flat-square)](https://packagist.org/packages/spatie/activitylog)

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

### Configurations
```php 
 /*
    |--------------------------------------------------------------------------
    | Also log to Laravel's default log handler
    |--------------------------------------------------------------------------
    |
    | If "alsoLogInDefaultLog" the activity will also be logged in the default
    | Laravel logger handler
    |
    */
    'alsoLogInDefaultLog' => true,

    /*
    |--------------------------------------------------------------------------
    | Max age in months for log records
    |--------------------------------------------------------------------------
    |
    | When running the cleanLog-command all recorder older than the number of months
    | specified here will be deleted
    |
    */
    'deleteRecordsOlderThanMonths' => 2,

    /*
    |--------------------------------------------------------------------------
    | Fallback user id if no user is logged in
    |--------------------------------------------------------------------------
    |
    | If you don't specify a user id when logging some activity and no
    | user is logged in, this id will be used.
    |
    */
    'defaultUserId' => '',

    /*
    |--------------------------------------------------------------------------
    | Model for the users
    |--------------------------------------------------------------------------
    |
    | While saving or retrieving activity per user, we need to know which model
    | we are relating to, this model will be used
    |
    */
    'activity_user_model' => '\Ceb\Models\User',

    /*
    |--------------------------------------------------------------------------
    | Verify if the user is authenticated by this model
    |--------------------------------------------------------------------------
    |
    | Before we do any activity log, we need to know if the user who is doing it
    | is authenticated or not, if he is authenticate then we get his id
    |
    */
    'activity_auth_model' => '\Sentry::check', // For example this is same as Sentry::check()

    /*
    |--------------------------------------------------------------------------
    | Authenticated user id object
    |--------------------------------------------------------------------------
    |
    | For us to know if we need to use the logged in user for logs, we need to 
    | first now if the user is authenticated or not.
    |
    */
    'activity_authenticated_user_model' => '\Sentry::getUser',

    /*
    |--------------------------------------------------------------------------
    | User id field to be called 
    |--------------------------------------------------------------------------
    |
    | In other to avoid undefiened property errors, we will call activity model
    | with this defined field if the user is authenticated only
    |
    */
    'user_id_field_name' => 'id',
  ```


## Usage

### Manual logging

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
Any Eloquent activity you would like to record needs to be returned by getRecordActivityEvents method. Per default we have enabled few activities.
Feel free 

```php 

    /*
    |--------------------------------------------------------------------------
    | User id field to be called 
    |--------------------------------------------------------------------------
    |
    | Eloquent models fire several events, allowing you to hook into various 
    | points in the model's lifecycle using the following methods  Events
    | allow you to easily execute code each time a specific model class 
    | is saved or updated in the database.with this defined field
    | if the user is authenticated only
    |
    */
    'record_activity_events' => [
                                    // 'creating', // Whenever a new model is being saved for the first time
                                    'created',     // Whenever a new model is saved for the first time
                                    // 'updating', // If a model already existed in the database
                                    'updated',     // If a model already existed in the database
                                    // 'saving',   // If a model already existed in the database and the save method is called
                                    //'saved',     // If a model already existed in the database and the save method is called
                                    'deleting',    // Whenever a model is being deleted
                                    'deleted',     // Whenever a model is being deleted
                                    // 'restoring', // Whenever a model is being restored
                                    // 'restored'  // Whenever a model is being restored
                                ],
```

In the LogsActivity trait, we have created getActivityDescriptionForEvent() method for you and have added awesomeness of all eloquent event and the 
current model name is used as the object name for this activity, However you are free to overright it and define your own messages in the model.

Here is how it looks like:

```php
   /**
    * Describe the event message
    * @param  string $eventName  name of the event
    * @return string     
    */
   public function getActivityDescriptionForEvent($eventName)
    {
           // Getting classname without namespace and use it to identify the object
           $modelName = join('', array_slice(explode('\\', get_class($this)), -1));
           switch ($eventName) {
            case 'creating':
                 $activity = $modelName . ' was creating';
                 break; 
            case 'created':
                 $activity = $modelName . ' was created';
                 break; 
            case 'updating':
                 $activity = $modelName . ' was updating';
                 break; 
            case 'updated':
                 $activity = $modelName . ' was updated';
                 break; 
            case 'saving':
                 $activity = $modelName . ' was saving';
                 break; 
            case 'saved':
                 $activity = $modelName . ' was saved';
                 break; 
            case 'deleting':
                 $activity = $modelName . ' was deleting';
                 break; 
            case 'deleted':
                 $activity = $modelName . ' was deleted';
                 break; 
            case 'restoring':
                 $activity = $modelName . ' was restoring';
                 break; 
            case 'restored':
                 $activity = $modelName . ' was restored';
                 break;
            default:
                $activity = '';
            break;
          }
          return $activity;
        }
```
The result of this function will be logged, unless the result is an empty string.

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
