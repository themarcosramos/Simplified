<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserRegistration()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'cpf_cnpj' => '30030030001',
            'email' => 'john@example.com',
            'password' => 'PasswordExample123',
            'is_merchant' => false
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'is_merchant' => false
        ]);
    }
}