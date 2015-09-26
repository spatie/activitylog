<?php

namespace Spatie\Activitylog\Models;

use Eloquent;
use Config;

class Activity extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activity_log';


    protected $guarded = ['id'];

    /**
     * Get the user that the activity belongs to.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(Config::get('activity_user_model'), 'user_id');
    }

    /**
     * Get the model object of the activity
     *
     * @return object
     */

    public function loggable()
    {
        return $this->morphTo();
    }
}
