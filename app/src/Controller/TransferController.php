<?php

namespace App\Controller;

use App\DTO\TransferDTO;
use App\Transfer\TransferExecutorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Money transfers
 */
class TransferController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var TransferExecutorInterface
     */
    private $transferExecutor;

    /**
     * TransferController constructor.
     *
     * @param ValidatorInterface        $validator
     * @param TransferExecutorInterface $transferExecutor
     */
    public function __construct(ValidatorInterface $validator, TransferExecutorInterface $transferExecutor)
    {
        $this->validator        = $validator;
        $this->transferExecutor = $transferExecutor;
    }
    
    /**
     * Run money transfer between users wallets
     *
     * @param Request $request
     *
     * @Route("/transfer", name="transfer_do", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function do(Request $request): JsonResponse
    {
        $amount         = (string)$request->request->get('amount');
        $sourceWalletId = (int)$request->request->get('source_wallet_id');
        $destWalletId   = (int)$request->request->get('dest_wallet_id');

        $transferDto = new TransferDTO($amount, $sourceWalletId, $destWalletId);
        $this->validator->validate($transferDto);

        try {
            $this->transferExecutor->execute($transferDto);
        } catch (\Throwable $throw) {
            return new JsonResponse(['success' => false, 'message' => $throw->getMessage()], 500);
        }

        return new JsonResponse(['success' => true]);
    }
}