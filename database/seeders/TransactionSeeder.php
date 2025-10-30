<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Services\LedgerService;
use Illuminate\Database\Seeder;


class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    private $ledgerService;

    public function __construct(LedgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
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
            'Rent Expense' => [
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

        $chankSize = 500;
        $total = 5000;

        for($i = 0; $i <= $total; $i += $chankSize){

            Transaction::factory()->count($chankSize)->create()->each(function($transaction) use($accountsData, $accountArray){

                $accountName = $transaction->account->name;
                $type = $transaction['type'] == 'credit' ? 'debit' : 'credit';
                $randomKey = array_rand($accountsData[$accountName][$type]);
                $accountName = $accountsData[$transaction->account->name][$type][$randomKey];

                $account = $accountArray[$accountName] ?? null;

                if($account){
                    $opositeTransaction = $transaction->create([
                        'account_id' => $account->id,
                        'date' => date('Y-m-d'),
                        'type' =>  $type,
                        'amount' => $transaction->amount,
                        'note' => $account->code . ' '. $account->name,
                    ]);

                    $this->ledgerService->updateBalance($opositeTransaction);
                }
            });
        }
    }
}
