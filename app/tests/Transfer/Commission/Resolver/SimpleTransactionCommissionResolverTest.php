<?php

namespace App\Tests\Transfer\Commission\Resolver;

use App\Entity\Transaction;
use App\Transfer\Commission\Resolver\SimpleTransactionCommissionResolver;
use PHPUnit\Framework\TestCase;

class SimpleTransactionCommissionResolverTest extends TestCase
{
    /**
     * @param string $fraction
     * @param int $amount
     * @param int $expectedCommission
     *
     * @dataProvider dataProvider
     */
    public function testResolve(string $fraction, int $amount, int $expectedCommission)
    {
        $simpleTransactionCommissionResolver = new SimpleTransactionCommissionResolver($fraction);

        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $simpleTransactionCommissionResolver->resolve($transaction);

        $this->assertEquals($expectedCommission, $transaction->getCommission());
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