<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\VoteController;
use App\Models\Kandidat;
use App\Models\CalonDpm;
use App\Models\Mahasiswa;
use App\Models\Setting;
use App\Services\VoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendProofOfVoteJob;
use Mockery;

class VoteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $voteService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock VoteService
        $this->voteService = Mockery::mock(VoteService::class);
        $this->app->instance(VoteService::class, $this->voteService);

        $this->controller = new VoteController($this->voteService);

        // Prevent logging during tests
        Log::shouldReceive('warning')->byDefault();
        Log::shouldReceive('error')->byDefault();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function testIndexRedirectsWhenVotingNotStarted()
    {
        // Set voting start time in the future
        Setting::create(['key' => 'voting_start_time', 'value' => Carbon::now()->addDay()->format('Y-m-d H:i:s')]);
        Setting::create(['key' => 'voting_end_time', 'value' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s')]);

        // Mock Auth to return a logged-in user (who will be logged out)
        $mahasiswa = Mahasiswa::factory()->create();
        Auth::shouldReceive('guard')->with('mahasiswa')->andReturnSelf();
        Auth::shouldReceive('user')->andReturn($mahasiswa);
        Auth::shouldReceive('logout')->once();

        $response = $this->controller->index();

        $this->assertEquals(route('login.mahasiswa'), $response->getTargetUrl());
        $this->assertStringContainsString('belum dimulai', session('error'));
    }

    /** @test */

}
