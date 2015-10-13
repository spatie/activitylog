<?php

namespace Spatie\Activitylog;

use Illuminate\Config\Repository;
use Illuminate\Auth\Guard;
use Spatie\Activitylog\Handlers\BeforeHandler;
use Spatie\Activitylog\Handlers\DefaultLaravelHandler;
use Request;
use Config;

class ActivitylogSupervisor
{
    /**
     * @var array logHandlers
     */
    protected $logHandlers = [];

    protected $auth;

    protected $config;

    /**
     * Create the logsupervisor using a default Handler
     * Also register Laravels Log Handler if needed.
     *
     * @param Handlers\ActivitylogHandlerInterface $logHandler
     * @param Handlers\BeforeHandlerInterface      $beforeHandler
     * @param Repository                           $config
     * @param Guard                                $auth
     */
    public function __construct(Handlers\ActivitylogHandlerInterface $logHandler, Handlers\BeforeHandler $beforeHandler, Repository $config, Guard $auth)
    {
        $this->config = $config;

        $this->logHandlers[] = $logHandler;

        if ($this->config->get('activitylog.alsoLogInDefaultLog')) {
            $this->logHandlers[] = new DefaultLaravelHandler();
        }

        if ($beforeCallback = $this->config->get('activitylog.beforeCallback')) {
            $this->beforeHandler = new $beforeCallback;
        } else {
            $this->beforeHandler = $beforeHandler;
        }

        $this->auth = $auth;
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
        if ($this->beforeHandler->ignore()) {
            return false;
        }

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
     * @param object|int $userId
     *
     * @return int
     */
    public function normalizeUserId($userId)
    {
        if (is_numeric($userId)) {
            return $userId;
        }

        if (is_object($userId)) {
            return $userId->id;
        }

        if ($this->auth->check()) {
            return $this->auth->user()->id;
        }

        if (is_numeric($this->config->get('activitylog.defaultUserId'))) {
            return $this->config->get('activitylog.defaultUserId');
        };

        return '';
    }
}
