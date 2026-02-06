<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTokenAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_token(): void
    {
        $user = User::factory()->primary()->create([
            'email' => 'primary@example.com',
        ]);

        $res = $this->postJson('/api/auth/login', [
            'email' => 'primary@example.com',
            'password' => 'password',
        ]);

        $res->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'email', 'type', 'customer_id']]);
    }

    public function test_protected_route_requires_token(): void
    {
        $res = $this->getJson('/api/user');
        $res->assertUnauthorized(); // 401
    }
}