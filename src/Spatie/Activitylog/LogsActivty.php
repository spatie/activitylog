<?php namespace Spatie\ActivityFeed\Traits;

use Activity;
use Spatie\Activitylog\LogsActivityInterface;

trait LogsActivity {

    protected static function bootLogsActivity()
    {
        foreach(static::getRecordActivityEvents() as $eventName)
        {
            static::$eventName(function(LogsActivityInterface $model) use($eventName){

                $message = $model->getActivityDescriptionForEvent($eventName);

                if ($message == '')
                {
                    $className = strtolower((new ReflectionClass($model))->getShortName());

                    $message = $className . ' - ' . $eventName;
                }

                Activity::log($message);

            });
        }
    }

    protected static function getRecordActivityEvents()
    {
        if(isset(static::$recordEvents))
        {
            return static::$recordEvents;
        }

        return [
            'created', 'deleted', 'updated',
        ];
    }

}
