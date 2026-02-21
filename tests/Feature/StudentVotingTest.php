<?php

namespace Tests\Feature;

use App\Models\Kandidat;
use App\Models\Mahasiswa;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentVotingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test student login.
     */
    public function test_student_can_login(): void
    {
        $mahasiswa = Mahasiswa::create([
            'nim' => '12345678',
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => 'password',
        ]);

        $this->actingAs($mahasiswa, 'mahasiswa');
        $this->assertAuthenticated('mahasiswa');
    }

    /**
     * Test student can vote.
     */
    public function test_student_can_vote_for_kandidat(): void
    {
        // 1. Create Candidate
        $kandidat = Kandidat::create([
            'no_urut' => 1,
            'nama_ketua' => 'Ketua Test',
            'nama_wakil' => 'Wakil Test',
            'visi' => 'Visi Test',
            'misi' => 'Misi Test',
            'status_aktif' => true,
        ]);

        // 2. Create Student
        $mahasiswa = Mahasiswa::create([
            'nim' => '87654321',
            'name' => 'Voter Student',
            'password' => 'password',
            'access_code' => 'TOKEN123',
            'attended_at' => now(), 
        ]);

        // 3. Act as Student & Vote
        $response = $this->actingAs($mahasiswa, 'mahasiswa')
                         ->post(route('vote.store'), [
                             'kandidat_id' => $kandidat->id,
                             'type' => 'presma',
                         ]);

        // 4. Assert Success
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('votes', [
            'mahasiswa_id' => $mahasiswa->id,
        ]);
        
        $mahasiswa->refresh();
        $this->assertNotNull($mahasiswa->voted_at);
    }

    /**
     * Test CRITICAL: student cannot vote twice (One User One Vote).
     */
    public function test_student_cannot_vote_twice(): void
    {
        // 1. Setup Student & Candidate
        $kandidat = Kandidat::create([
            'no_urut' => 1,
            'nama_ketua' => 'Ketua Test',
            'nama_wakil' => 'Wakil Test',
            'status_aktif' => true,
        ]);

        $mahasiswa = Mahasiswa::create([
            'nim' => '99999999',
            'name' => 'Double Voter',
            'password' => 'password',
            'access_code' => 'TOKEN999',
            'attended_at' => now(), 
        ]);

        // 2. First Vote (Should Succeed)
        $response1 = $this->actingAs($mahasiswa, 'mahasiswa')
                          ->post(route('vote.store'), [
                              'kandidat_id' => $kandidat->id,
                              'type' => 'presma',
                          ]);
        
        $response1->assertSessionHas('success');
        $this->assertDatabaseCount('votes', 1);

        // 3. Second Vote (Should Fail)
        // Refresh token/session handled by actingAs automatically usually, but let's just hit route again
        $response2 = $this->actingAs($mahasiswa, 'mahasiswa')
                          ->from(route('student.dashboard')) // simulate coming from dashboard
                          ->post(route('vote.store'), [
                              'kandidat_id' => $kandidat->id,
                              'type' => 'presma',
                          ]);

        // 4. Assert Failure
        // VoteController catches exception and returns back with 'error'
        $response2->assertSessionHas('error'); // Controller returns with('error', ...)
        
        // 5. Verify database still only has 1 vote for this student
        $this->assertDatabaseCount('votes', 1);
    }
}
