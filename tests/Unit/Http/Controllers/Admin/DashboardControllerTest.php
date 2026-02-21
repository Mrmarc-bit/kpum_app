<?php

namespace Tests\Unit\Controllers\Admin;

use Tests\TestCase;
use App\Http\Controllers\Admin\DashboardController;
use App\Services\DashboardService;
use Illuminate\View\View;
use Mockery;

class DashboardControllerTest extends TestCase
{
    protected $dashboardServiceMock;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dashboardServiceMock = Mockery::mock(DashboardService::class);
        $this->controller = new DashboardController($this->dashboardServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }


}
