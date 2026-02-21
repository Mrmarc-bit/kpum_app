<?php

namespace Tests\Unit\Http\Controllers\Admin;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Services\AnalyticsService;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AnalyticsControllerTest extends TestCase
{
    protected $analyticsServiceMock;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->analyticsServiceMock = $this->createMock(AnalyticsService::class);
        $this->controller = new AnalyticsController($this->analyticsServiceMock);
    }

    /** @test */

}
