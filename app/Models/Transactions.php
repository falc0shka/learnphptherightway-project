<?php

declare(strict_types = 1);

namespace App\Models;

use App\Model;

class Transactions extends Model
{
    protected $transactions = [];

    public function getTransactions(): array
    {
        $result = $this->db->query('SELECT * FROM transactions ORDER BY date');
        foreach ($result as $row) {
          $this->transactions[] = $row;
        }
        return $this->transactions;
    }
    public function uploadTransactions(array $files): void
    {

        foreach ($files as $file) {
            $sql = 'INSERT INTO transactions (date, checking, description, amount)
                    VALUES (:date, :checking, :description, :amount)';
            $sth = $this->db->prepare($sql);
            if (($file = fopen($file, 'r')) !== false) {
                fgetcsv($file);
                while (($row = fgetcsv($file)) !== false) {
                    $sth->execute([
                      'date'=> date('y-m-d', strtotime($row[0])),
                      'checking'=> $row[1],
                      'description'=> $row[2],
                      'amount' => (float) str_replace(['$', ','], ['', ''], $row[3])
                    ]);
                }
            }
        }
    }
    public function calculateTotals(): array
    {
        $incomeTotal = 0;
        $expenseTotal = 0;
        $netTotal = 0;
        foreach ($this->transactions as $transaction) {
          if ($transaction['amount'] > 0) {
            $incomeTotal += $transaction['amount'];
          }
          else {
            $expenseTotal += $transaction['amount'];
          }
          $netTotal += $transaction['amount'];
        }
        return [
          'income' => $incomeTotal,
          'expense' => $expenseTotal,
          'net' => $netTotal,
        ];
    }
    
}