<?php

namespace Spatie\Activitylog;

interface LogsActivityInterface
{
    /**
     * Get the message that needs to be logged for the given event.
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getActivityDescriptionForEvent($eventName);
}
