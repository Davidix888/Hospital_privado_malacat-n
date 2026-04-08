<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_is_displayed(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Inicio de sesi&oacute;n', false);
    }

    public function test_user_can_authenticate_from_login_screen(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_user_can_authenticate_with_mixed_case_username(): void
    {
        $user = User::factory()->create([
            'username' => 'ldixquiac',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'username' => 'LdIxQuIaC',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/login')->post('/login', [
            'username' => $user->username,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
    }
}
