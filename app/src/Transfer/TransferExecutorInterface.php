<?php


namespace App\Transfer;


use App\DTO\TransferDTO;

/**
 * Resolve how to execute transfer
 */
interface TransferExecutorInterface
{
    /**
     * Execute transfer by given DTO
     *
     * @param TransferDTO $transferDTO
     *
     * @return void
     */
    public function execute(TransferDTO $transferDTO): void;
}