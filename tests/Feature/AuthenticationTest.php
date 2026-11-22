<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('gods.index'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_register_with_a_username(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'username' => 'test_user',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/dashboard');

        $user = User::where('username', 'test_user')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertFalse($user->is_admin);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'test_user',
        ]);
    }

    public function test_user_can_log_in_with_username(): void
    {
        $user = User::factory()->create([
            'username' => 'athena',
            'password' => 'secret-password',
        ]);

        $this->post('/login', [
            'username' => 'athena',
            'password' => 'secret-password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }
}
