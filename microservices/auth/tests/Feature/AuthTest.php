<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    public function test_registration()
    {
        $userData = User::factory()->make()->toArray();
        $userData['password'] = 'password';
        $userData['password_confirmation'] = 'password';

        $response = $this->postJson('/api/auth/register', $userData);


        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'name' => $userData['name'],
        ]);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'token',
                'token_type',
                'expires_in',
            ],
        ]);

        $response->assertStatus(201);
    }

    public function test_login()
    {
        $userData = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $userData['email'],
            'password' => 'password',
        ]);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
                'token',
                'token_type',
                'expires_in',
            ],
        ]);

        $response->assertStatus(200);
    }

    public function test_logout()
    {
        $userData = User::factory()->create();

        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => $userData['email'],
            'password' => 'password',
        ]);

        $token = $loginResponse->json('data.token');

        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson('/api/auth/logout');

        $logoutResponse->assertStatus(200);
        $logoutResponse->assertJsonStructure([
            'success',
            'message'
        ]);
    }
}
