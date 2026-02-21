<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResetElectionController;
use App\Models\User;
use App\Services\ResetElectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class ResetElectionControllerTest extends TestCase
{
    protected $resetElectionServiceMock;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resetElectionServiceMock = Mockery::mock(ResetElectionService::class);
        $this->controller = new ResetElectionController($this->resetElectionServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testIndexReturnsCorrectView()
    {
        $view = $this->controller->index();

        $this->assertEquals('admin.reset.index', $view->getName());
        $this->assertArrayHasKey('title', $view->getData());
        $this->assertEquals('Reset Election', $view->getData()['title']);
    }

    public function testStoreValidationFailsWithoutPassword()
    {
        $request = Request::create('/admin/reset-election', 'POST', [
            'confirm_text' => 'RESET ELECTION'
        ]);

        $this->expectException(ValidationException::class);
        $this->controller->store($request);
    }

    public function testStoreValidationFailsWithIncorrectConfirmText()
    {
        $request = Request::create('/admin/reset-election', 'POST', [
            'password' => 'password123',
            'confirm_text' => 'WRONG TEXT'
        ]);

        $this->expectException(ValidationException::class);
        $this->controller->store($request);
    }

    public function testStoreFailsWithIncorrectUserPassword()
    {
        $user = User::factory()->make(['password' => Hash::make('correct_password')]);
        Auth::shouldReceive('user')->andReturn($user);
        Hash::shouldReceive('check')->with('incorrect_password', $user->password)->andReturn(false);

        $request = Request::create('/admin/reset-election', 'POST', [
            'password' => 'incorrect_password',
            'confirm_text' => 'RESET ELECTION'
        ]);

        $response = $this->controller->store($request);

        $this->assertNotNull($response->getSession()->get('error'));
        $this->assertEquals('Password salah. Tidak dapat melanjutkan reset.', $response->getSession()->get('error'));
        $this->assertTrue($response->isRedirect());
    }

    public function testStoreSuccessfullyResetsElection()
    {
        $user = User::factory()->make(['password' => Hash::make('correct_password')]);
        Auth::shouldReceive('user')->andReturn($user);
        Hash::shouldReceive('check')->with('correct_password', $user->password)->andReturn(true);

        $this->resetElectionServiceMock->shouldReceive('nukeElectionData')
            ->once()
            ->with($user, Mockery::any(), Mockery::any());

        $request = Request::create('/admin/reset-election', 'POST', [
            'password' => 'correct_password',
            'confirm_text' => 'RESET ELECTION'
        ]);
        $request->setLaravelSession($this->app['session.store']); // Required for redirect with session

        $response = $this->controller->store($request);

        $this->assertNotNull($response->getSession()->get('success'));
        $this->assertStringContainsString('Pemilihan berhasil di-reset!', $response->getSession()->get('success'));
        $this->assertTrue($response->isRedirect());
        $this->assertEquals(route('admin.dashboard'), $response->getTargetUrl());
    }
}