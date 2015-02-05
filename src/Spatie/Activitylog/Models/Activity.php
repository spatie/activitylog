<?php namespace Spatie\Activitylog\Models;

use Eloquent;
use DateTime;
use Config;

class Activity extends Eloquent {

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

    protected $guarded = array('id');

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::saving(function($activity) {
            $activity->created_at = new DateTime();
        });
    }
}
