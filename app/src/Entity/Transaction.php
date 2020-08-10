<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Information about transfers between wallets
 *
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 * @ORM\Table(name="transaction")
 */
class Transaction
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The wallet from which money was debited
     *
     * @var Wallet
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Wallet")
     * @ORM\JoinColumn(name="source_wallet_id", referencedColumnName="id")
     */
    private $sourceWallet;

    /**
     * The wallet to which money come
     *
     * @var Wallet
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Wallet")
     * @ORM\JoinColumn(name="destination_wallet_id", referencedColumnName="id")
     */
    private $destinationWallet;

    /**
     * Sum of transaction
     *
     * @var int
     *
     * @ORM\Column(type="bigint")
     *
     * @Assert\NotBlank
     * @Assert\Positive
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint")
     *
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public $commission;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Wallet
     */
    public function getSourceWallet(): Wallet
    {
        return $this->sourceWallet;
    }

    /**
     * @param Wallet $sourceWallet
     */
    public function setSourceWallet(Wallet $sourceWallet): void
    {
        $this->sourceWallet = $sourceWallet;
    }

    /**
     * @return Wallet
     */
    public function getDestinationWallet(): Wallet
    {
        return $this->destinationWallet;
    }

    /**
     * @param Wallet $destinationWallet
     */
    public function setDestinationWallet(Wallet $destinationWallet): void
    {
        $this->destinationWallet = $destinationWallet;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getCommission(): int
    {
        return $this->commission;
    }

    /**
     * @param int $commission
     */
    public function setCommission(int $commission): void
    {
        $this->commission = $commission;
    }
}