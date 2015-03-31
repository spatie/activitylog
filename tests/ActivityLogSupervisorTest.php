<?php

use Illuminate\Support\Collection;

class ActivityLogSupervistorTest extends PHPUnit_Framework_TestCase
{

    protected $logHandler;
    protected $activityLogSupervisor;
    protected $config;

    public function setUp()
    {
        $this->logHandler = Mockery::mock('\Spatie\Activitylog\Handlers\EloquentHandler');
        $this->config = Mockery::mock('\Illuminate\Config\Repository');

        $this->config->shouldReceive('get')->andReturn(false);
        $this->activityLogSupervisor = new \Spatie\Activitylog\ActivitylogSupervisor($this->logHandler, $this->config);
    }

    /**
     * @test
     */
    public function testGetVisitorsAndPageViews()
    {
        $this->assertTrue(true);
    }
}