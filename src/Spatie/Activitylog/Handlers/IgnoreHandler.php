<?php

namespace Spatie\Activitylog\Handlers;

use Log;

class IgnoreHandler implements IgnoreHandlerInterface
{
    /**
     * Ignore logging if this function returns true.
     *
     * @return bool
     */
	 public function ignore()
     {
        return false;
     }
}
