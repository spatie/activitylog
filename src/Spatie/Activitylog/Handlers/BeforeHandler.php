<?php

namespace Spatie\Activitylog\Handlers;

class BeforeHandler implements BeforeHandlerInterface
{
    /**
     * Don't log if this function returns true.
     *
     * @return bool
     */
     public function ignore()
     {
         return false;
     }
}
