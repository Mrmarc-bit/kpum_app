<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * Test user initials generation.
     */
    public function test_user_initials_generation(): void
    {
        $user = new User([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertEquals('JD', $user->initials());
    }

    /**
     * Test user initials with single name.
     */
    public function test_user_initials_single_name(): void
    {
        $user = new User([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $this->assertEquals('A', $user->initials());
    }

    /**
     * Test user initials with three names.
     */
    public function test_user_initials_three_names(): void
    {
        $user = new User([
            'name' => 'Muhammad Rizky Ramadhan',
            'email' => 'rizky@example.com',
        ]);

        // Logic takes first 2 words
        $this->assertEquals('MR', $user->initials());
    }

    /**
     * Test user attributes assignment.
     */
    public function test_user_attributes(): void
    {
        $user = new User([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('admin', $user->role);
    }
}
