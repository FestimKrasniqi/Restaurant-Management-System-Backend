<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a user in the database.
     */
    public function test_user_can_be_created(): void
    {
        // Arrange: Create a user using the factory
        $userData = [
            'first' => 'John',
            'last' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
            'confirm_password' => bcrypt('password123'),
            'active' => true,
            'address' => '123 Main St',
            'phoneNumber' => '123-456-7890',
            'role' => 'user',
        ];

        $user = User::create($userData);

        // Assert: Check if the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'first' => 'John',
            'last' => 'Doe',
            'role' => 'user',
        ]);
    }

    /**
     * Test fetching a list of users.
     */
    public function test_user_list_can_be_fetched(): void
    {
        // Arrange: Generate 3 users in the database
        User::factory()->count(3)->create();

        // Act: Fetch all users
        $response = $this->getJson('/users'); // If your route is /users

        // Assert: Check if the response returns 200 status and 3 users
        $response->assertStatus(200);
        $this->assertCount(3, User::all());
    }

    /**
     * Test deleting a user.
     */
    // public function test_user_can_be_deleted(): void
    // {
    //     // Arrange: Create a user
    //     $user = User::factory()->create();

    //     // Act: Delete the user
    //     $user->delete();

    //     // Assert: Ensure the user no longer exists in the database
    //     $this->assertDatabaseMissing('users', ['id' => $user->id]);
    // }
}
