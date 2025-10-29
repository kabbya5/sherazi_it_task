<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LedgerService {

    public function addTransaction(array $data){
        DB::beginTransaction();

       try {
            $payMode = $data['pay_mode'];
            $type = $data['type'];
            $amount = $data['amount'];
            $date = $data['date'];
            $note = $data['note'] ?? null;

            [$debitAccount, $creditAccount] = $this->getAccounts($type, $payMode);

            $debitTransaction = Transaction::create([
                'account_id' => $debitAccount->id,
                'date' => $date,
                'type' => 'debit',
                'amount' => $amount,
                'note' => $note,
            ]);

            $this->updateBalance($debitTransaction);


            $creditTransaction = Transaction::create([
                'account_id' => $creditAccount->id,
                'date' => $date,
                'type' => 'credit',
                'amount' => $amount,
                'note' => $note,
            ]);

            $this->updateBalance($creditTransaction);

            DB::commit();

            return response()->json([
                'status'  => 1,
                'message' => 'Transaction added successfully',
                'data'    => [
                    'debit_transaction'  => $debitTransaction,
                    'credit_transaction' => $creditTransaction,
                ]
            ], 201);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => 0,
                'message' => 'Failed to add transaction',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    private function getAccounts(string $type,string $payMode) : array{
        $map = [
            'sale' => [
                'cash' => ['debit' => 'Cash', 'credit' => 'Sales'],
                'credit' => ['debit' => 'Accounts Receivable', 'credit' => 'Sales'],
                'bank' => ['debit' => 'Bank', 'credit' => 'Sales'],
            ],
            'purchase' => [
                'cash' => ['debit' => 'Purchase', 'credit' => 'Cash'],
                'credit' => ['debit' => 'Purchase', 'credit' => 'Accounts Payable'],
                'bank' => ['debit' => 'Purchase', 'credit' => 'Bank'],
            ],
        ];

        $selected = $map[$type][$payMode] ?? null;

        if (!$selected) {
            throw new \Exception("Invalid mapping for type: {$type}, mode: {$payMode}");
        }

        $debit = Account::where('name', $selected['debit'])->firstOrFail();
        $credit = Account::where('name', $selected['credit'])->firstOrFail();

        return [$debit, $credit];
    }

    private function updateBalance(Transaction $transaction): void {
        $account = $transaction->account;

        if(!$account){
            throw new Exception('Account not Found.');
        }

        if($transaction->type == 'debit'){
            $account->balance += $transaction->amount;
        }else{
            $account->balance -= $transaction->amount;
        }

        $account->save();
    }

    public function getReport($account_id = null) : Collection{
        $transactions =  DB::table('accounts as a')
            ->join('transactions as t', 't.account_id', '=', 'a.id')
            ->select(
                'a.id as account_id',
                'a.name as account_name',
                'a.code as account_code',
                'a.balance as balance',
                DB::raw("SUM(CASE WHEN t.type = 'debit' THEN t.amount ELSE 0 END) as total_debit"),
                DB::raw("SUM(CASE WHEN t.type = 'credit' THEN t.amount ELSE 0 END) as total_credit"),
            )->when($account_id, fn($query, $account_id) => $query->where('a.id', $account_id))
            ->groupBy('a.id', 'a.name', 'a.code', 'a.balance')
            ->get();


        return new Collection($transactions);
    }
}
