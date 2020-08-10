<?php

namespace App\Tests\Currency;

use App\Currency\AmountTransformerFactory;
use App\Currency\Bitcoin\BitcoinAmountTransformer;
use App\Entity\Currency;
use PHPUnit\Framework\TestCase;

class AmountTransformerFactoryTest extends TestCase
{
    public function testCreate()
    {
        $amountTransformerFactory = new AmountTransformerFactory();

        $currency = new Currency();
        $currency->setName(Currency::BITCOIN_NAME);

        $amountTransformer = $amountTransformerFactory->create($currency);

        $this->assertThat($amountTransformer, $this->isInstanceOf(BitcoinAmountTransformer::class));
    }

    public function testCreateException()
    {
        $this->expectException(\RuntimeException::class);

        $amountTransformerFactory = new AmountTransformerFactory();

        $currency = new Currency();
        $currency->setName('test');

        $amountTransformerFactory->create($currency);
    }
}