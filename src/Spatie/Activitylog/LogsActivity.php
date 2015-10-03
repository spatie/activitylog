<?php

namespace Spatie\Activitylog;
use Activity;
use Spatie\Activitylog\Exceptions\UserNotAuthenticatedException;
use Illuminate\Contracts\Events\Dispatcher ;
use Sentry;
use Config;
use Illuminate\Config\Repository;
trait LogsActivity
{
    /**
     * Relationship with activities table.
     * 
     * @return object
     */
    public function activities(){
        return $this->morphMany('\Spatie\Activitylog\Models\Activity','loggable');
    }

    /**
     * Boot method for this Eloquent
     * @return mixed
     */
    public static function boot()
    {
        parent::boot(); 
        
        // Get configurations
        $userId = Config::get('activitylog.defaultUserId');
        $userIdFieldName = Config::get('activitylog.user_id_field_name','id');

        // If the user is authenticated then try to get the user ID
        // Who is performing this activity
        if (call_user_func(Config::get('activitylog.activity_auth_model'))) {
          $userId = call_user_func(Config::get('activitylog.activity_authenticated_user_model'))->$userIdFieldName;       
        }

        //Lesten to all event as specified in the getRecordActivityEvents
        foreach (static::getRecordActivityEvents() as $eventName) 
        {
            static::$eventName(function (LogsActivityInterface $model) use ($eventName,$userId) 
            {
                $message = $model->getActivityDescriptionForEvent($eventName);
                //We won't do anything if the message for this activity isnot available
                if ($message != '') 
                {
                 $model->activities()->create(['user_id'=>$userId,'text'=>$message]);
                }
            });
        }

    }


    /**
     * Set the default events to be recorded if the $recordEvents
     * property does not exist on the model.
     *
     * @return array
     */
    protected static function getRecordActivityEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }
        return Config::get('activitylog.record_activity_events', []);
    }

   /**
    * Describe the event message
    * @param  string $eventName  name of the event
    * @return string     
    */
   public function getActivityDescriptionForEvent($eventName)
    {
           // Gettin classname without namespace and use it to identify the object
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
}