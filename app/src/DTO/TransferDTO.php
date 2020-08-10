<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TransferDTO
 */
class TransferDTO
{
    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Regex("/^(\d{1,10})(\.\d{1,8})?$/")
     */
    public $amount;

    /**
     * @var int
     *
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public $sourceWalletId;

    /**
     * @var int
     *
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public $destWalletId;

    /**
     * Transfer constructor.
     *
     * @param string $amount
     * @param int $sourceWalletId
     * @param int $destWalletId
     */
    public function __construct(string $amount, int $sourceWalletId, int $destWalletId)
    {
        $this->amount         = $amount;
        $this->sourceWalletId = $sourceWalletId;
        $this->destWalletId   = $destWalletId;
    }
}