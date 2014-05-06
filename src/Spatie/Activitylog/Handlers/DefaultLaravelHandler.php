<?php

namespace Spatie\Activitylog\Handlers;

use Log;
use Spatie\Activitylog\Handlers\ActivitylogHandler as HandlerInterface;

class DefaultLaravelHandler implements HandlerInterface {

    /**
     *
     * Log activity in Laravels log handler
     *
     * @param string $text
     * @param string $user
     * @param array $attributes
     * @return boolean
     *
     */
    public function log($text, $user = '', $attributes = [])
    {
        $logText = $text;
        $logText .= ($user ? ' (by user_id '  . $user->id . ')' : '');
        $logText .= (count($attributes)) ? PHP_EOL . print_r($attributes, true) : '';

        Log::info($logText);

        return true;
    }


    /**
     *
     * Clean old log records
     *
     * @param int $maxAgeInMonths
     * @return boolean
     */

    public function cleanLog($maxAgeInMonths)
    {
        //this handler can't clean it's records

        return true;
    }
}