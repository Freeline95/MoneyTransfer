<?php

namespace App\Currency\Bitcoin;

use App\Currency\AmountTransformerInterface;

/**
 * Bitcoin values transform
 */
class BitcoinAmountTransformer implements AmountTransformerInterface
{
    /**
     * Max value, which we need multiply bitcoin for get int value
     *
     * 10^8
     */
    private const CONVERTING_KOEFFICIENT = 100000000;

    /**
     * @param string $amount
     *
     * @return int
     */
    public function normalize(string $amount)
    {
        if (!preg_match("/^(\d{1,10})(\.\d{1,8})?$/", $amount)) {
            throw new \RuntimeException('Invalid argument for convert bitcoin to int. Amount: ' . $amount);
        }

        return (int)bcmul($amount, self::CONVERTING_KOEFFICIENT);
    }

    /**
     * @param int $amount
     *
     * @return string
     */
    public function denormalize(int $amount): string
    {
        $denormalizedAmount = bcdiv($amount, self::CONVERTING_KOEFFICIENT, strlen(self::CONVERTING_KOEFFICIENT) - 1);

        return rtrim($denormalizedAmount, '0.');
    }
}