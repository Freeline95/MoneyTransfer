<?php

namespace App\Transfer\Commission\Resolver;

use App\Entity\Transaction;
use App\Transfer\Commission\Calculator\PercentCommissionCalculator;
use App\Transfer\Commission\TransactionCommissionResolverInterface;

/**
 * Resolve commission by transaction
 */
class SimpleTransactionCommissionResolver implements TransactionCommissionResolverInterface
{
    /**
     * @var PercentCommissionCalculator
     */
    private $percentCommissionCalculator;

    /**
     * SimpleTransactionCommissionResolver constructor.
     *
     * @param string $appCommission
     */
    public function __construct(string $appCommission)
    {
        $this->percentCommissionCalculator = new PercentCommissionCalculator($appCommission);
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(Transaction $transaction): void
    {
        $commission = $this->percentCommissionCalculator->calculate($transaction);
        $transaction->setCommission($commission);
    }
}