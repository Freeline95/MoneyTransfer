<?php

namespace App\Transfer\Commission\Calculator;

use App\Entity\Transaction;
use App\Transfer\Commission\CommissionCalculatorInterface;

/**
 * Calculate commission by fraction
 */
class PercentCommissionCalculator implements CommissionCalculatorInterface
{
    /**
     * Minimal commission for deal
     */
    private const MIN_COMMISSION = 1;

    /**
     * @var string
     */
    private $fraction;

    /**
     * PercentCommissionCalculator constructor.
     *
     * @param string $appCommission
     */
    public function __construct(string $appCommission)
    {
        if (!preg_match('/^0(\.[0-9]+)?$/', $appCommission)) {
            throw new \RuntimeException('Wrong fraction for calculate percent commission');
        }

        $this->fraction = $appCommission;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(Transaction $transaction): int
    {
        $commission = (int)bcmul($transaction->getAmount(), $this->fraction);

        if ($commission === 0) {
            return self::MIN_COMMISSION;
        } else {
            return $commission;
        }
    }
}