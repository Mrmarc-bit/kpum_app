<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\ProofOfVoteController;
use App\Models\Mahasiswa;
use App\Services\ProofOfVoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response; // For streamDownload return type

class ProofOfVoteControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testDownloadRedirectsToLoginIfUserNotAuthenticated()
    {
        Auth::shouldReceive('guard')->with('mahasiswa')->andReturnSelf();
        Auth::shouldReceive('user')->andReturn(null);

        $proofServiceMock = Mockery::mock(ProofOfVoteService::class);
        $controller = new ProofOfVoteController();

        $response = $controller->download($proofServiceMock);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString(route('login.mahasiswa'), $response->getTargetUrl());
        $this->assertSessionHas('error', 'Silakan login terlebih dahulu.');
    }

    public function testDownloadRedirectsBackIfUserHasNotVoted()
    {
        $user = new Mahasiswa(['nim' => '12345', 'name' => 'Test User']);
        $user->voted_at = null;
        $user->dpm_voted_at = null;

        Auth::shouldReceive('guard')->with('mahasiswa')->andReturnSelf();
        Auth::shouldReceive('user')->andReturn($user);

        $proofServiceMock = Mockery::mock(ProofOfVoteService::class);
        $controller = new ProofOfVoteController();

        $response = $controller->download($proofServiceMock);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSessionHas('error', 'Anda belum melakukan pemilihan.');
        // Laravel's back() redirect doesn't set a specific target URL in unit tests,
        // but we can check for the session flash.
    }

    public function testDownloadGeneratesPdfIfUserVotedForPresident()
    {
        $user = new Mahasiswa(['nim' => '12345', 'name' => 'Test User']);
        $user->voted_at = now();
        $user->dpm_voted_at = null;

        Auth::shouldReceive('guard')->with('mahasiswa')->andReturnSelf();
        Auth::shouldReceive('user')->andReturn($user);

        $pdfMock = Mockery::mock(PDF::class);
        $pdfMock->shouldReceive('download')
                ->once()
                ->with('Bukti_Pilih_12345.pdf')
                ->andReturn(response()->streamDownload(function () {}, 'Bukti_Pilih_12345.pdf'));

        $proofServiceMock = Mockery::mock(ProofOfVoteService::class);
        $proofServiceMock->shouldReceive('generatePdf')
                         ->once()
                         ->with(Mockery::on(function ($arg) use ($user) {
                             return $arg->is($user);
                         }))
                         ->andReturn($pdfMock);

        $controller = new ProofOfVoteController();
        $response = $controller->download($proofServiceMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('attachment; filename="Bukti_Pilih_12345.pdf"', $response->headers->get('Content-Disposition'));
    }


}
