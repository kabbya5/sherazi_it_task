<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::insert([
            [
                'code' => 'AC-001',
                'name' => 'Cash',
                'description' => 'Cash account for petty cash and on-hand cash',
            ],
            [
                'code' => 'AC-002',
                'name' => 'Bank',
                'description' => 'Bank account for all transactions via bank',
            ],
            [
                'code' => 'AC-003',
                'name' => 'Purchase',
                'description' => 'Purchase account for recording all purchases',
            ],
            [
                'code' => 'AC-004',
                'name' => 'Sales',
                'description' => 'Sales account for all sales transactions',
            ],
            [
                'code' => 'AC-005',
                'name' => 'Accounts Receivable',
                'description' => 'Money to be received from customers',
            ],
            [
                'code' => 'AC-006',
                'name' => 'Accounts Payable',
                'description' => 'Money to be paid to suppliers',
            ],
            [
                'code' => 'AC-007',
                'name' => 'Rent Expense',
                'description' => 'Expenses for rent payments',
            ],
            [
                'code' => 'AC-008',
                'name' => 'Utilities Expense',
                'description' => 'Expenses for electricity, water, and utilities',
            ],
            [
                'code' => 'AC-009',
                'name' => 'Discount',
                'description' => 'Discounts allowed or received in transactions',
            ],
            [
                'code' => 'AC-010',
                'name' => 'Sales Revenue',
                'Description' => 'Sales Revenue',
            ],
        ]);
    }
}
