<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Models\Transactions;
use App\View;

class TransactionsController
{
    public function transactions(): View
    {
        $transactionsModel = new Transactions();

        return View::make('transactions',
        [
          'transactions' => $transactionsModel->getTransactions(),
          'totals' => $transactionsModel->calculateTotals(),
        ]);
    }
    public function upload(): void
    {
        $transactionsModel = new Transactions();

        header('Location: /transactions');
        
        $transactionsModel->uploadTransactions($_FILES['transactions']["tmp_name"]);
        exit;
    }
}
