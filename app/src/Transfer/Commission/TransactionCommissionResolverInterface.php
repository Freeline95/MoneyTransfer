<?php

namespace App\Transfer\Commission;

use App\Entity\Transaction;

/**
 * Transaction commission resolver
 */
interface TransactionCommissionResolverInterface
{
    /**
     * @param Transaction $transaction
     *
     * @return void
     */
    public function resolve(Transaction $transaction): void;
}