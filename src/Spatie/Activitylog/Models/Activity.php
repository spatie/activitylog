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

    /**
     * Get the user that the activity belongs to.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(Config::get('auth.model'), 'user_id');
    }

    protected $guarded = ['id'];
}
