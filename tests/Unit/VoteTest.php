<?php

namespace Tests\Unit;

use App\Models\Vote;
use PHPUnit\Framework\TestCase;

class VoteTest extends TestCase
{
    /**
     * Test vote attributes assignment.
     */
    public function test_vote_attributes(): void
    {
        $vote = new Vote([
            'mahasiswa_id' => 1,
            'kandidat_id' => 2,
        ]);

        $this->assertEquals(1, $vote->mahasiswa_id);
        $this->assertEquals(2, $vote->kandidat_id);
    }

    /**
     * Test encryption_meta casting.
     */
    public function test_encryption_meta_casting(): void
    {
        $meta = ['key' => 'value', 'iv' => '123'];
        
        $vote = new Vote([
            'encryption_meta' => $meta,
        ]);

        // When retrieved, it should be an array because of casting
        $this->assertIsArray($vote->encryption_meta);
        $this->assertEquals('value', $vote->encryption_meta['key']);
    }
}
