<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class MoneyTransferTest extends TestCase
{
    use RefreshDatabase;

    public function testMoneyTransferBetweenUsers()
    {
        $payer = User::factory()->create(['balance' => 1000, 'is_merchant' => false]);
        $payee = User::factory()->create(['balance' => 500]);

        $response = $this->actingAs($payer)->postJson('/api/transfer', [
            'value' => 200,
            'payer' => $payer->id,
            'payee' => $payee->id
        ]);

        $response->assertStatus(201);
        $this->assertEquals(800, $payer->fresh()->balance);
        $this->assertEquals(700, $payee->fresh()->balance);
    }
}