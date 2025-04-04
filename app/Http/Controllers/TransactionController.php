<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function transfer(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric|min:0.01',
            'payer' => 'required|integer',
            'payee' => 'required|integer'
        ]);
    
        $payer = User::findOrFail($request->payer);
        $payee = User::findOrFail($request->payee);
    
        if ($payer->is_merchant) {
            return response()->json(['message' => 'Merchants cannot send money.'], 403);
        }
    
        if ($payer->balance < $request->value) {
            return response()->json(['message' => 'Insufficient funds.'], 403);
        }
    
        $authorizationResponse = Http::get('https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc');
        $authorizationData = $authorizationResponse->json();
    
        if (is_null($authorizationData) || !isset($authorizationData['message']) || $authorizationData['message'] !== 'Autorizado') {
            return response()->json(['message' => 'Transaction not authorized.'], 403);
        }
    
        DB::beginTransaction();
        try {
            $payer->balance -= $request->value;
            $payee->balance += $request->value;
            $payer->save();
            $payee->save();
    
            $transaction = Transaction::create([
                'payer_id' => $payer->id,
                'payee_id' => $payee->id,
                'value' => $request->value
            ]);
    
            DB::commit();
            if ($transaction) {
                Http::get('https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6');
            }
            return response()->json(['message' => 'Transaction completed successfully.', 'data' => $transaction], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction failed.', 'error' => $e->getMessage()], 500);
        }
    }
}
