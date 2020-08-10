<?php

namespace App\Tests\Controller;

use App\Entity\Currency;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransferControllerTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        static::$kernel = self::bootKernel();

        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * This test was wrote only for test task - for easy check that system can work
     *
     * @param int    $sourceBalance
     * @param string $transferAmount
     * @param int    $expectedSourceBalance
     * @param int    $expectedDestBalance
     *
     * @dataProvider dataProvider
     */
    public function testDo(
        int    $sourceBalance,
        string $transferAmount,
        int    $expectedSourceBalance,
        int    $expectedDestBalance
    )
    {
        $currencyRepository = $this->entityManager->getRepository(Currency::class);
        $currency           = $currencyRepository->findOneBy(['name' => Currency::BITCOIN_NAME]);

        if (!isset($currency)) {
            $currency = new Currency();
            $currency->setName(Currency::BITCOIN_NAME);

            $this->entityManager->persist($currency);
        }

        $sourceWallet = new Wallet();
        $sourceWallet->setCurrency($currency);
        // 10 BTC
        $sourceWallet->setBalance($sourceBalance);

        $destinationWallet = new Wallet();
        $destinationWallet->setCurrency($currency);
        $destinationWallet->setBalance(0);

        $this->entityManager->persist($sourceWallet);
        $this->entityManager->persist($destinationWallet);

        $this->entityManager->flush();

        $client = static::$kernel->getContainer()->get('test.client');

        $client->request(
            'POST',
            '/transfer',
            [
                'amount'           => $transferAmount,
                'source_wallet_id' => $sourceWallet->getId(),
                'dest_wallet_id'   => $destinationWallet->getId()
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        /** @var Wallet $sourceWallet */
        $sourceWallet = $this->entityManager->find(Wallet::class, $sourceWallet->getId());
        /** @var Wallet $destWallet */
        $destWallet   = $this->entityManager->find(Wallet::class, $destinationWallet->getId());

        $this->assertEquals($expectedSourceBalance, $sourceWallet->getBalance());
        $this->assertEquals($expectedDestBalance, $destWallet->getBalance());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function dataProvider()
    {
        return [
            [1000000000, '1', 898500000, 100000000],
            [2200000010000001, '21000000.10000001', 68499999850000, 2100000010000001],
            [2, '0.00000001', 0, 1]
        ];
    }
}