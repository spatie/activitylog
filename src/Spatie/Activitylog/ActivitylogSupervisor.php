<?php namespace Spatie\Activitylog;

use Spatie\Activitylog\Handlers\DefaultLaravelHandler;
use Auth;
use Request;
use Config;

class ActivitylogSupervisor
{
    /**
     * @var array logHandlers
     */
    protected $logHandlers = [];

    /**
     * Create the logsupervisor using a default Handler
     * Also register Laravels Log Handler if needed.
     *
     * @param Handlers\ActivitylogHandler $handler
     */
    public function __construct(Handlers\ActivitylogHandler $handler)
    {
        $this->logHandlers[] = $handler;
        if (Config::get('activitylog.alsoLogInDefaultLog')) {
            $this->logHandlers[] = new DefaultLaravelHandler();
        }
    }

    /**
     * Log some activity to all registered log handlers.
     *
     * @param $text
     * @param string $userId
     *
     * @return bool
     */
    public function log($text, $userId = '')
    {
        $userId = $this->normalizeUserId($userId);

        $ipAddress = Request::getClientIp();

        foreach ($this->logHandlers as $logHandler) {
            $logHandler->log($text, $userId, compact('ipAddress'));
        }

        return true;
    }

    /**
     * Clean out old entries in the log.
     *
     * @return bool
     */
    public function cleanLog()
    {
        foreach ($this->logHandlers as $logHandler) {
            $logHandler->cleanLog(Config::get('activitylog.deleteRecordsOlderThanMonths'));
        }

        return true;
    }

    /**
     * Normalize the user id.
     *
     * @param $userId
     *
     * @return int
     */
    public function normalizeUserId($userId)
    {
        if (is_object($userId)) {
            return $userId->id;
        }

        if ($userId == '' && Auth::check()) {
            return Auth::user()->id;
        }

        return '';
    }
}
