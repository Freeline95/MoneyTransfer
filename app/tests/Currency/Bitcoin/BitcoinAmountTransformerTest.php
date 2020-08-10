<?php

namespace App\Tests\Currency\Bitcoin;

use App\Currency\Bitcoin\BitcoinAmountTransformer;
use PHPUnit\Framework\TestCase;

class BitcoinAmountTransformerTest extends TestCase
{
    /**
     * @param string $amount
     * @param int    $expected
     *
     * @dataProvider dataProvider
     */
    public function testNormalize(string $amount, int $expected)
    {
        $bitcoinAmountTransformer = new BitcoinAmountTransformer();

        $normalizedAmount = $bitcoinAmountTransformer->normalize($amount);

        $this->assertEquals($expected, $normalizedAmount);
    }

    /**
     * @param string $expected
     * @param int    $amount
     *
     * @dataProvider dataProvider
     */
    public function testDenormalize(string $expected, int $amount)
    {
        $bitcoinAmountTransformer = new BitcoinAmountTransformer();

        $denormalizedAmount = $bitcoinAmountTransformer->denormalize($amount);

        $this->assertEquals($expected, $denormalizedAmount);
    }

    public function testNormalizeException()
    {
        $this->expectException(\RuntimeException::class);

        $bitcoinAmountTransformer = new BitcoinAmountTransformer();

        $bitcoinAmountTransformer->normalize('qwe');
    }

    /**
     * Data provider
     *
     * @return array[]
     */
    public function dataProvider()
    {
        return [
            ['1', 100000000],
            ['0.00000001', 1],
            ['123123123', 12312312300000000],
            ['1.12312323', 112312323],
            ['1.32', 132000000]
        ];
    }
}