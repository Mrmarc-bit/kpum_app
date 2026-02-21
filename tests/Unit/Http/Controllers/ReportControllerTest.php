<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\ReportController;
use App\Models\User;
use App\Models\ReportFile;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Mockery;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $reportService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the ReportService dependency
        $this->reportService = Mockery::mock(ReportService::class);
        $this->app->instance(ReportService::class, $this->reportService);

        $this->controller = new ReportController($this->reportService);

        // Mock Auth facade
        Auth::shouldReceive('id')->andReturn(1);
        Auth::shouldReceive('user')->andReturn(
            (object)['id' => 1, 'role' => 'admin'] // Default user for most tests
        );

        // Mock Storage facade
        Storage::shouldReceive('disk')->with('public')->andReturnSelf();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test the index method for different user roles.
     */

}
