<?php namespace Spatie\Activitylog;

use Spatie\Activitylog\Handlers\DefaultLaravelHandler;
use User;
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
     *
     * Create the logsupervisor using a default Handler
     * Also register Laravels Log Handler if needed
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
     *
     * Log some activity to all registered log handlers
     *
     * @param $text
     * @param string $user
     * @return bool
     */
    public function log($text, $user ='')
    {
        if ($user == '') {
            $user = Auth::user() ?: '';
        }
        if (is_numeric($user)) {
            $user = User::findOrFail($user);
        }

        $ipAddress = Request::getClientIp();
        $userAgent = Request::server('HTTP_USER_AGENT');

        foreach($this->logHandlers as $logHandler) {
            $logHandler->log($text, $user, compact('ipAddress','userAgent'));
        }

        return true;
    }

    public function cleanLog()
    {
        foreach($this->logHandlers as $logHandler) {
            $logHandler->cleanLog(Config::get('activitylog.deleteRecordsOlderThanMonths'));
        }

        return true;
    }
}