<?php

namespace Tests\Unit\Controllers\Admin;

use Tests\TestCase;
use App\Http\Controllers\Admin\CalonDpmController;
use App\Models\CalonDpm;
use App\Services\CalonDpmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;

class CalonDpmControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $calonDpmServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the CalonDpmService
        $this->calonDpmServiceMock = Mockery::mock(CalonDpmService::class);

        // Bind the mock to the service container
        $this->app->instance(CalonDpmService::class, $this->calonDpmServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_displays_the_index_page_with_calon_dpm_data()
    {
        // Arrange
        CalonDpm::factory()->count(3)->create();

        // Act
        $response = $this->get(route('admin.calon_dpm.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.calon_dpm.index');
        $response->assertViewHas('calons');
        $this->assertCount(3, $response->viewData('calons'));
    }

    /** @test */
    public function it_displays_the_create_calon_dpm_form()
    {
        // Act
        $response = $this->get(route('admin.calon_dpm.create'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.calon_dpm.create');
        $response->assertViewHas('title', 'Tambah Calon DPM');
    }

    /** @test */
    public function it_can_store_a_new_calon_dpm_with_image()
    {
        // Arrange
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $data = [
            'nama' => $this->faker->name,
            'fakultas' => $this->faker->word,
            'prodi' => $this->faker->word,
            'visi' => $this->faker->paragraph,
            'misi' => $this->faker->paragraph,
            'deskripsi' => $this->faker->sentence,
            'status_aktif' => true,
            'urutan_tampil' => 1,
            'nomor_urut' => '01',
        ];

        $this->calonDpmServiceMock
            ->shouldReceive('create')
            ->once()
            ->with(
                Mockery::subset(array_merge($data, ['status_aktif' => true])), // Ensure status_aktif is boolean true
                Mockery::type(UploadedFile::class)
            )
            ->andReturn(CalonDpm::factory()->make($data)); // Return a dummy model

        // Act
        $response = $this->post(route('admin.calon_dpm.store'), array_merge($data, ['foto' => $file]));

        // Assert
        $response->assertRedirect(route('admin.calon_dpm.index'));
        $response->assertSessionHas('success', 'Calon DPM berhasil ditambahkan');
    }

    /** @test */

}
