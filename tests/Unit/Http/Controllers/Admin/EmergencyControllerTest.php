<?php

namespace Tests\Unit\Controllers\Admin;

use Tests\TestCase;
use App\Http\Controllers\Admin\EmergencyController;
use App\Models\AuditLog;
use App\Models\Kandidat;
use App\Models\CalonDpm;
use App\Models\Vote;
use App\Models\DpmVote;
use App\Models\Mahasiswa;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Carbon\Carbon;
use Mockery;

class EmergencyControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Mock facades
        Auth::shouldReceive('user')->andReturn((object)['id' => 1, 'password' => Hash::make('admin_password')]);
        Hash::shouldReceive('check')->andReturnUsing(function ($password, $hashedPassword) {
            return $password === 'admin_password'; // Simplified check for tests
        });

        // Mock models to prevent actual DB calls
        Kandidat::unguard();
        CalonDpm::unguard();
        Mahasiswa::unguard();
        Vote::unguard();
        DpmVote::unguard();
        AuditLog::unguard();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function testIndexReturnsCorrectViewAndData()
    {
        // Mock data
        $mockKandidats = collect([(object)['id' => 1, 'no_urut' => 1, 'nama' => 'Kandidat A']]);
        $mockCalonDpms = collect([(object)['id' => 1, 'nomor_urut' => 1, 'nama' => 'DPM A']]);
        $mockNonVotersCount = 50;

        // Mock model calls
        Kandidat::shouldReceive('orderBy->get')->andReturn($mockKandidats);
        CalonDpm::shouldReceive('orderBy->get')->andReturn($mockCalonDpms);
        Mahasiswa::shouldReceive('whereNull->whereNull->count')->andReturn($mockNonVotersCount);

        $controller = new EmergencyController();
        $response = $controller->index();

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('admin.emergency.override', $response->name());
        $this->assertArrayHasKey('title', $response->getData());
        $this->assertArrayHasKey('kandidats', $response->getData());
        $this->assertArrayHasKey('calonDpms', $response->getData());
        $this->assertArrayHasKey('nonVotersCount', $response->getData());
        $this->assertEquals($mockKandidats, $response->getData()['kandidats']);
        $this->assertEquals($mockCalonDpms, $response->getData()['calonDpms']);
        $this->assertEquals($mockNonVotersCount, $response->getData()['nonVotersCount']);
    }

    /** @test */
    public function testOverrideValidationFails()
    {
        $controller = new EmergencyController();
        $request = Request::create('/admin/emergency/override', 'POST', []); // Empty request

        // Expect validation exception
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->override($request);
    }

    /** @test */
    public function testOverrideFailsWithIncorrectPassword()
    {
        $user = Mockery::mock('App\Models\User');
        $user->password = Hash::make('correct_password'); // Simulate hashed password
        Auth::shouldReceive('user')->andReturn($user);
        Hash::shouldReceive('check')->with('wrong_password', $user->password)->andReturn(false);

        $request = Request::create('/admin/emergency/override', 'POST', [
            'kandidat_id' => 1,
            'calon_dpm_id' => 1,
            'jumlah_suara' => 1,
            'password' => 'wrong_password',
            'confirm_text' => 'EXECUTE OVERRIDE'
        ]);

        $controller = new EmergencyController();
        $response = $controller->override($request);

        $this->assertRedirect();
        $this->assertStringContainsString('Password salah', session('error'));
    }

    /** @test */

}
