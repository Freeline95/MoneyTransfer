<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\Wallet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $currency = $this->createCurrency($manager);

        $this->createWallets($manager, $currency);

        $manager->flush();
    }

    private function createCurrency(ObjectManager $manager): Currency
    {
        $currency = new Currency();

        $currency->setName(Currency::BITCOIN_NAME);

        $manager->persist($currency);

        return $currency;
    }

    private function createWallets(ObjectManager $manager, Currency $currency): void
    {
        $sourceWallet = new Wallet();

        $sourceWallet->setBalance(100000000);
        $sourceWallet->setCurrency($currency);

        $manager->persist($sourceWallet);

        $destinationWallet = new Wallet();

        $destinationWallet->setBalance(0);
        $destinationWallet->setCurrency($currency);

        $manager->persist($destinationWallet);
    }
}
