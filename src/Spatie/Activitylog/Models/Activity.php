<?php

namespace Spatie\Activitylog\Models;

use Eloquent;
use Config;
use Exception;

class Activity extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activity_log';

    /**
     * Get the user that the activity belongs to.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo($this->getAuthModelName(), 'user_id');
    }

    public function getAuthModelName()
    {
        if (config('activitylog.userModel')) {
            return config('activitylog.userModel');
        }
        
        //laravel 5.0 - 5.1
        if (! is_null(config('auth.model'))) {
            return config('auth.model');
        }

        //laravel 5.2
        if (! is_null(config('auth.providers.users.model'))) {
            return config('auth.providers.users.model');
        }

        throw new Exception('could not determine the model name for users');
    }

    protected $guarded = ['id'];
}
