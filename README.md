# Log the activities of your users

[![License](https://poser.pugx.org/spatie/googlesearch/license.png)](https://packagist.org/packages/spatie/googlesearch)


## Installation

This package can be installed through Composer.

```js
{
    "require": {
		"spatie/activitylog": "dev-master"
	}
}
```

This service provider must be registered.

```php

// app/config/app.php

'providers' => [
    '...',
    'Spatie\Activitylog\ActivityServiceProvider'
];
```

You'll also need to publish and run the migration in order to create the db-table.

```
php artisan migrate:publish spatie/activitylog
php artisan migrate 
```

Activitylog also comes with a facade, which provides an easy way to call it.


```php

// app/config/app.php

'aliases' => array(
	...
	'Activity' => 'Spatie\Activitylog\ActivityFacade',
)
```




## Usage

```php

Geocoder::getCoordinatesForQuery('Infinite Loop 1, Cupertino');

/* 
  This function returns an array with keys
  "lat" =>  37.331741000000001
  "lng" => -122.0303329
  "accuracy" => "ROOFTOP"
*/
```

The accuracy key can contain these values:
- 'ROOFTOP'
- 'RANGE_INTERPOLATED'
- 'GEOMETRIC_CENTER'
- 'APPROXIMATE'

You can read more information about these values [on the Google Geocoding API Page](https://developers.google.com/maps/documentation/geocoding/ "Google Geocoding API")

When an address is not found accuracy will contain 'NOT_FOUND'
