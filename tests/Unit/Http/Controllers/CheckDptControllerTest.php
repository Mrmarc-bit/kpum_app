<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\CheckDptController;
use App\Models\Mahasiswa;
use App\Models\Setting;
use Illuminate\Http\Request;
use Mockery;

class CheckDptControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure no actual database interactions for Mahasiswa/Setting unless explicitly needed
        // For these tests, we'll mock them.
    }

    public function testIndexReturnsViewWithSettings()
    {
        // Mock the Setting model
        $settingsMock = Mockery::mock('alias:' . Setting::class);
        $settingsMock->shouldReceive('pluck')
                     ->with('value', 'key')
                     ->andReturn(collect(['key1' => 'value1', 'key2' => 'value2']));

        $controller = new CheckDptController();
        $response = $controller->index();

        $this->assertEquals('check-dpt.index', $response->name());
        $this->assertArrayHasKey('settings', $response->getData());
        $this->assertEquals(['key1' => 'value1', 'key2' => 'value2'], $response->getData()['settings']);
    }

    public function testSearchReturnsMahasiswaIfFound()
    {
        $nim = '12345';
        $mahasiswa = Mahasiswa::factory()->make([
            'nim' => $nim,
            'name' => 'John Doe',
            'prodi' => 'Informatika',
        ]);

        // Mock the Mahasiswa model to return our fake instance
        Mockery::mock('alias:' . Mahasiswa::class)
            ->shouldReceive('where->first')
            ->andReturn($mahasiswa);

        $request = Request::create('/check-dpt/search', 'POST', ['nim' => $nim]);
        $controller = new CheckDptController();
        $response = $controller->search($request);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'found',
            'data' => [
                'name' => 'Jo** Do**', // Masked name
                'prodi' => 'Informatika',
                'nim' => $nim,
                'status' => 'Terdaftar dalam DPT',
            ]
        ]);
    }

    public function testSearchReturnsNotFoundIfMahasiswaDoesNotExist()
    {
        $nim = '99999';

        // Mock the Mahasiswa model to return null
        Mockery::mock('alias:' . Mahasiswa::class)
            ->shouldReceive('where->first')
            ->andReturn(null);

        $request = Request::create('/check-dpt/search', 'POST', ['nim' => $nim]);
        $controller = new CheckDptController();
        $response = $controller->search($request);

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 'not_found',
            'message' => 'Data tidak ditemukan. Pastikan NIM yang Anda masukkan benar.'
        ]);
    }

    public function testSearchValidationFailsForMissingNim()
    {
        $request = Request::create('/check-dpt/search', 'POST', []);
        $controller = new CheckDptController();

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->expectExceptionMessage('NIM wajib diisi.');

        $controller->search($request);
    }

    public function testSearchValidationFailsForInvalidNimFormat()
    {
        $request = Request::create('/check-dpt/search', 'POST', ['nim' => 'NIM-INVALID!']);
        $controller = new CheckDptController();

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->expectExceptionMessage('NIM hanya boleh berisi huruf dan angka.');

        $controller->search($request);
    }
}