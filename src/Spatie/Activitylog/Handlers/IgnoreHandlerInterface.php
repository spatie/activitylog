<?php

namespace Spatie\Activitylog\Handlers;

interface IgnoreHandlerInterface
{
    /**
     * Ignore logging if this function returns true.
     *
     * @return bool
     */
    public function ignore();
}
