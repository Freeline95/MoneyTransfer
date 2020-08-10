<?php


namespace App\Currency;

/**
 * Transform amount for system or for user
 */
interface AmountTransformerInterface
{
    /**
     * Normalize for db storing
     *
     * @param string $amount
     *
     * @return mixed
     */
    public function normalize(string $amount): int;

    /**
     * Denormalize for user
     *
     * @param int $amount
     *
     * @return mixed
     */
    public function denormalize(int $amount): string;
}