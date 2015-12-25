<?php

use Illuminate\Support\Facades\Auth as Auth;
use Spatie\Activitylog\ActivitylogSupervisor;

class ActivityLogSupervistorTest extends PHPUnit_Framework_TestCase
{
    protected $logHandler;
    protected $activityLogSupervisor;
    protected $config;

    public function setUp()
    {
        $this->logHandler = Mockery::mock('\Spatie\Activitylog\Handlers\EloquentHandler');
        $this->config = Mockery::mock('\Illuminate\Config\Repository');
        $this->auth = Mockery::mock('Illuminate\Contracts\Auth\Guard');

        $this->config->shouldReceive('get')->andReturn(false);
        $this->activityLogSupervisor = new ActivitylogSupervisor($this->logHandler, $this->config, $this->auth);
    }

    /**
     * @test
     */
    public function it_normalizes_an_empty_user_id_when_noone_is_logged_in()
    {
        $this->auth->shouldReceive('check')->andReturn(false);

        $normalizedUserId = $this->activityLogSupervisor->normalizeUserId('');

        $this->assertSame('', $normalizedUserId);
    }

    /**
     * @test
     */
    public function it_normalizes_an_empty_user_id_when_someone_is_logged_in()
    {
        $user = json_decode(json_encode(['id' => 123]), false);

        $this->auth->shouldReceive('check')->andReturn(true);
        $this->auth->shouldReceive('user')->andReturn($user);

        $normalizedUserId = $this->activityLogSupervisor->normalizeUserId('');

        $this->assertSame(123, $normalizedUserId);
    }

    /**
     * @test
     */
    public function it_normalizes_a_numeric_user_id()
    {
        $normalizedUserId = $this->activityLogSupervisor->normalizeUserId(123);

        $this->assertSame(123, $normalizedUserId);
    }
}
