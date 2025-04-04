<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testTransactionStore()
    {
        $payer = User::factory()->create(['balance' => 1000]);
        $payee = User::factory()->create(['is_merchant' => true]);

        $response = $this->actingAs($payer)->postJson('/api/transfer', [
            'value' => 100,
            'payer' => $payer->id,
            'payee' => $payee->id
        ]);

        Http::fake([
            'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6' => Http::response(['message' => 'Success'], 200),
            'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc' => Http::response(['message' => 'Autorizado'], 200), // Adiciona simulação de autorização
            '*' => Http::response(['message' => 'Not found'], 404)
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('transactions', [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'value' => 100
        ]);
    }

    public function testUserCanSendMoneyWithSufficientBalance()
    {
        $payer = User::factory()->create(['balance' => 1000]);
        $payee = User::factory()->create(['balance' => 500]);

        $response = $this->actingAs($payer)->postJson('/api/transfer', [
            'value' => 100,
            'payer' => $payer->id,
            'payee' => $payee->id
        ]);

        $response->assertStatus(201);
        $this->assertEquals(900, $payer->fresh()->balance);
        $this->assertEquals(600, $payee->fresh()->balance);
    }

    public function testMerchantCannotSendMoney()
    {
        $merchant = User::factory()->create(['is_merchant' => true, 'balance' => 1000]);
        $user = User::factory()->create(['balance' => 500]);

        $response = $this->actingAs($merchant)->postJson('/api/transfer', [
            'value' => 100,
            'payer' => $merchant->id,
            'payee' => $user->id
        ]);

        $response->assertStatus(403);
    }

}
