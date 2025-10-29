<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function run(): void
    {
        $accountsData = [
            'Cash' => [
                'debit' => ['Bank', 'Accounts Payable'],
                'credit' => ['Accounts Receivable', 'Rent Expense', 'Utilities Expense'],
            ],
            'Bank' => [
                'debit' => ['Bank', 'Cash'],
                'credit' => ['Cash', 'Accounts Payable'],
            ],
            'Sales' => [
                'debit' => ['Accounts Receivable'],
                'credit' => ['Sales Revenue'],
            ],
            'Purchase' => [
                'debit' => ['Purchase'],
                'credit' => ['Accounts Payable', 'Cash'],
            ],
            'Rent' => [
                'debit' => ['Rent Expense'],
                'credit' => ['Cash'],
            ],
            'Utilities Expense' => [
                'debit' => ['Utilities Expense'],
                'credit' => ['Cash'],
            ],
            'Accounts Receivable' => [
                'debit' => ['Accounts Receivable'],
                'credit' => ['Sales Revenue'],
            ],
            'Accounts Payable' => [
                'debit' => ['Purchase',],
                'credit' => ['Accounts Payable'],
            ],

            'Sales Revenue' => [
                'debit' => ['Cash', 'Accounts Receivable'],
                'credit' => ['Sales Revenue'],
            ],

            'Expenses' => [
                'debit' => ['Expenses'],
                'credit' => ['Cash'],
            ],
        ];

        $accountArray = Account::all()->keyBy('name');

        $chankSize = 10;
        $total = 20;

        for($i = 0; $i <= $total; $i += $chankSize){

            Transaction::factory()->count($chankSize)->create()->each(function($transaction) use($accountsData, $accountArray){

                $accountName = $transaction->account->name;
                $type = $transaction['type'] == 'credit' ? 'debit' : 'credit';
                $randomKey = array_rand($accountsData[$accountName][$type]);
                $accountName = $accountsData[$transaction->account->name][$type][$randomKey];

                $account = $accountArray[$accountName] ?? null;

                if($account){
                    $this->transactionService->addTransaction(
                        $account->id,
                        date('Y-m-d'),
                        $type,
                        $transaction->amount,
                        $transaction->note,
                    );

                    $this->transactionService->updateBalance($transaction, $transaction->type);
                }
            });
        }
    }
}
