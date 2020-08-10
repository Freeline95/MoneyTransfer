<?php


namespace App\Currency;

/**
 * Transform amount for
 */
interface AmountTransformerInterface
{
    public function normalize(string $amount);

    public function denormalize(int $amount);
}