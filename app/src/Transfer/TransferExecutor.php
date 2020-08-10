<?php

namespace App\Transfer;

use App\Currency\AmountTransformerFactory;
use App\DTO\TransferDTO;
use App\Entity\Transaction;
use App\Entity\Wallet;
use App\Transfer\Commission\TransactionCommissionResolverInterface;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Transfer runner
 */
class TransferExecutor implements TransferExecutorInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var AmountTransformerFactory
     */
    private $amountTransformerFactory;

    /**
     * @var TransactionCommissionResolverInterface
     */
    private $transactionCommissionResolver;

    /**
     * TransferExecutor constructor.
     *
     * @param EntityManagerInterface                 $entityManager
     * @param AmountTransformerFactory               $amountTransformerFactory
     * @param TransactionCommissionResolverInterface $transactionCommissionResolver
     */
    public function __construct(
        EntityManagerInterface                 $entityManager,
        AmountTransformerFactory               $amountTransformerFactory,
        TransactionCommissionResolverInterface $transactionCommissionResolver
    )
    {
        $this->entityManager                 = $entityManager;
        $this->amountTransformerFactory      = $amountTransformerFactory;
        $this->transactionCommissionResolver = $transactionCommissionResolver;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Throwable
     */
    public function execute(TransferDTO $transferDTO): void
    {
        $this->entityManager->beginTransaction();

        try {
            /** @var Wallet $sourceWallet */
            $sourceWallet = $this->entityManager->find(
                Wallet::class,
                $transferDTO->sourceWalletId,
                LockMode::PESSIMISTIC_WRITE
            );

            /** @var Wallet $destWallet */
            $destWallet = $this->entityManager->find(
                Wallet::class,
                $transferDTO->destWalletId,
                LockMode::PESSIMISTIC_WRITE
            );

            if ($sourceWallet->getCurrency() !== $destWallet->getCurrency()) {
                throw new \RuntimeException('Doesnt support transfers between wallets with different currencies');
            }

            $amountTransformer = $this->amountTransformerFactory->create($sourceWallet->getCurrency());
            $normalizedAmount  = $amountTransformer->normalize($transferDTO->amount);


            $transaction = new Transaction();
            $transaction->setAmount($normalizedAmount);
            $transaction->setSourceWallet($sourceWallet);
            $transaction->setDestinationWallet($destWallet);

            $this->transactionCommissionResolver->resolve($transaction);
            $this->entityManager->persist($transaction);

            $newSourceWalletBalance = $sourceWallet->getBalance() - $normalizedAmount - $transaction->getCommission();

            if ($newSourceWalletBalance < 0) {
                throw new \RuntimeException('User have less balance, that need for transfer');
            }

            $sourceWallet->setBalance($newSourceWalletBalance);
            $this->entityManager->persist($sourceWallet);

            $newDestWalletBalance = $destWallet->getBalance() + $normalizedAmount;
            $destWallet->setBalance($newDestWalletBalance);
            $this->entityManager->persist($destWallet);

            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (\Throwable $e) {
            $this->entityManager->rollback();

            throw $e;
        }
    }
}