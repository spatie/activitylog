<?php

namespace Spatie\Activitylog\Handlers;

interface BeforeHandlerInterface
{
    /**
     * Don't log if this function returns true.
     *
     * @return bool
     */
    public function ignore();
}
