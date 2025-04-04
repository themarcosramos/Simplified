<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserRegistration()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'cpf_cnpj' => '12345678901',
            'is_merchant' => false
        ]);
        $response
            ->assertStatus(201)
            ->assertJson([
                'user' => [
                    'name' => 'Test User',
                    'email' => 'test@example.com'
                ],
                'message' => 'User successfully registered'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }
}
