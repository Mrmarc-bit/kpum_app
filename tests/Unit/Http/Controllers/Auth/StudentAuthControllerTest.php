<?php

namespace Tests\Unit\Http\Controllers\Auth;

use Tests\TestCase;
use App\Http\Controllers\Auth\StudentAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\Mahasiswa; // Assuming Mahasiswa is the user model for the 'mahasiswa' guard
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Mockery;

class StudentAuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock facades
        Auth::shouldReceive('guard')->andReturnSelf();
        Cookie::shouldReceive('get')->andReturn(null);
        Cookie::shouldReceive('queue')->andReturnSelf();
        Cookie::shouldReceive('forget')->andReturnSelf();
        Log::shouldReceive('info')->andReturnSelf();
        Log::shouldReceive('warning')->andReturnSelf();

        // Mock AuditLog model
        AuditLog::shouldReceive('create')->andReturn(new AuditLog());

        // Mock Setting model
        Setting::shouldReceive('get')->andReturn(null); // Default to null unless specified
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function testShowLoginFormGuest()
    {
        Auth::guard('mahasiswa')->shouldReceive('check')->andReturn(false);
        Cookie::shouldReceive('get')->with('saved_nim')->andReturn('12345');

        $controller = new StudentAuthController();
        $response = $controller->showLoginForm();

        $this->assertEquals('auth.student-login', $response->getName());
        $this->assertArrayHasKey('title', $response->getData());
        $this->assertEquals('Login Mahasiswa', $response->getData()['title']);
        $this->assertArrayHasKey('savedNim', $response->getData());
        $this->assertEquals('12345', $response->getData()['savedNim']);
    }

    /** @test */
    public function testShowLoginFormAuthenticated()
    {
        Auth::guard('mahasiswa')->shouldReceive('check')->andReturn(true);

        $controller = new StudentAuthController();
        $response = $controller->showLoginForm();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('student.dashboard'), $response->getTargetUrl());
    }

    /** @test */
    public function testLoginSuccessRedirectsToBilikSuara()
    {
        $mockUser = Mockery::mock(Mahasiswa::class);
        $mockUser->id = 1;
        $mockUser->name = 'Test Student';
        $mockUser->nim = '12345';

        $request = Request::create('/login', 'POST', [
            'nim' => '12345',
            'password' => '12345678',
            'access_code' => 'ABCDEF',
            'remember' => 'on',
        ]);

        Auth::guard('mahasiswa')->shouldReceive('attempt')
            ->with(['nim' => '12345', 'password' => '12345678', 'access_code' => 'ABCDEF'], true)
            ->andReturn(true);
        Auth::guard('mahasiswa')->shouldReceive('user')->andReturn($mockUser);

        // Mock voting time to be active
        Carbon::setTestNow(Carbon::parse('2024-01-01 10:00:00'));
        Setting::shouldReceive('get')->with('voting_start_time')->andReturn('2024-01-01 09:00:00');

        $controller = new StudentAuthController();
        $response = $controller->login($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/bilik-suara', $response->getTargetUrl());
        Cookie::shouldHaveReceived('queue')->with('saved_nim', '12345', 43200);
        AuditLog::shouldHaveReceived('create')->once()->with(Mockery::subset([
            'user_id' => 1,
            'action' => 'LOGIN: SUCCESS',
            'details' => 'Mahasiswa 12345 berhasil login.',
        ]));
    }

    /** @test */

}
