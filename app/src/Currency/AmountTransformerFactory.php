<?php

namespace App\Currency;

use App\Currency\Bitcoin\BitcoinAmountTransformer;
use App\Entity\Currency;

/**
 * Amount transformer creator
 */
class AmountTransformerFactory
{
    /**
     * Create amount transformer by currency
     *
     * @param Currency $currency
     * @return AmountTransformerInterface
     */
    public function create(Currency $currency): AmountTransformerInterface
    {
        if ($currency->getName() === Currency::BITCOIN_NAME) {
            return new BitcoinAmountTransformer();
        } else {
            throw new \RuntimeException('Unrecognized currency name ' . $currency->getName());
        }
    }
}