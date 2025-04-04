<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserIndex()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/users');
        $response->assertOk();
    }

    public function testRegistrationFailsWithDuplicateEmail()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'cpf_cnpj' => '12345678901',
            'email' => 'test@example.com',
            'password' => 'password',
            'is_merchant' => false
        ]);

        $response->assertStatus(422);
    }

    public function testRegistrationFailsWithDuplicateCpfCnpj()
    {
        $user = User::factory()->create(['cpf_cnpj' => '12345678901']);

        $response = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'cpf_cnpj' => '12345678901',
            'email' => 'jane@example.com',
            'password' => 'password',
            'is_merchant' => false
        ]);

        $response->assertStatus(422);
    }
}
