<?php

namespace App\Transfer\Commission;

use App\Entity\Transaction;

/**
 * Commission calculating
 */
interface CommissionCalculatorInterface
{
    /**
     * @param Transaction $transaction
     *
     * @return int
     */
    public function calculate(Transaction $transaction): int;
}