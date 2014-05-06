<?php

namespace Spatie\Activitylog\Handlers;

use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Handlers\ActivitylogHandler as HandlerInterface;

class EloquentHandler implements HandlerInterface {


    /**
     *
     * Log activity in an Eloquent model
     *
     * @param string $text
     * @param string $user
     * @param array $attributes
     * @return boolean
     *
     */

    public function log($text, $user = '', $attributes = [])

    {
        Activity::create(
            [
                'text'       =>$text,
                'user_id'    => ($user ? $user->id : null),
                'ip_address' => $attributes['ipAddress'],
                'user_agent' => $attributes['userAgent']
            ]
        );

        return true;
    }
}