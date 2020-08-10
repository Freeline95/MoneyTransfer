<?php

namespace App\Tests\Transfer\Commission\Calculator;

use App\Entity\Transaction;
use App\Transfer\Commission\Calculator\PercentCommissionCalculator;
use PHPUnit\Framework\TestCase;

class PercentCommissionCalculatorTest extends TestCase
{
    /**
     * @param string $fraction
     * @param int    $amount
     * @param int    $expected
     *
     * @dataProvider dataProvider
     */
    public function testCalculate(string $fraction, int $amount, int $expected)
    {
        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $percentCommissionCalculator = new PercentCommissionCalculator($fraction);
        $commission = $percentCommissionCalculator->calculate($transaction);

        $this->assertEquals($expected, $commission);
    }

    public function dataProvider()
    {
        return [
            ['0.015', 2200000000000001, 33000000000000],
            ['0.1', 2200000000, 220000000],
            ['0.000001', 234234234234, 234234],
            ['0.000654', 234234234234, 153189189],
            ['0.123456789', 888777666555444, 109725636847847]
        ];
    }
}