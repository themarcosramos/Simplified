<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class MerchantRestrictionsTest extends TestCase
{
    use RefreshDatabase;

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