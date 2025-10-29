<?php

namespace App\Http\Controllers\Api;

use App\Facades\Ledger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request){
        $account_id = $request->account_id;
        try{
            $transactions = Ledger::getReport($account_id);

            return response()->json([
                'status' => 1,
                'transactions' => $transactions,
            ]);
        }catch (\Throwable $error) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch transactions.',
                'error' => $error->getMessage(), // optional, remove in production
            ], 500);
        }
    }

    public function store(Request $request){
        $validated = $request->validate([
            'pay_mode' => 'required|string|in:cash,credit,bank',
            'date' => 'required|date',
            'type' => 'required|string|in:sale,purchase',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

         try {
            $response = Ledger::addTransaction($validated);
            return $response;
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Transaction failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
