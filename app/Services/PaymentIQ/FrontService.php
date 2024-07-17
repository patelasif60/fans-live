<?php

namespace App\Services\PaymentIQ;
use App\Services\PaymentIQ\Client;

/**
 * Front service class to handle Payment IQ payments and user accounts.
 */
class FrontService
{
    /**
     * Create a new service instance.
     *
     * @param ConsumerRepository $repository
     */
    public function __construct(Client $client)
    {
    	$this->clientService = $client;
    }

    /**
     * Process or validate user payments.
     *
     * @param $provider
     * @param $type
     * @param $method
     * @param $data
     */
    public function processPayment($provider, $type, $method, $data)
    {
       return $this->clientService->processPayment($provider, $type, $method,$data);
    }
    /**
     * Get user payment account.
     *
     * 
     */
    public function getUserPaymentAccounts($userId, $data)
    {
        return $this->clientService->getUsersPaymentAccount($userId, $data);
    }
     /**
     * Delete user payment account.
     *
     * 
     */
    public function deleteUserPaymentAccounts($userId, $accountId, $data)
    {
        return $this->clientService->deleteUsersPaymentAccount($userId, $accountId, $data);
    }

    /**
     * Get user's ongoing transactions.
     *
     * @param $userId
     * @param $data
     */
    public function getUsersOngoingTransactions($userId,$data)
    {
        return $this->clientService->getUsersOngoingTransactions($userId, $data);
    }

    /**
     * Get transaction status.
     *
     * @param $userId
     * @param $transactionId
     * @param $data
     */
    public function getTransactionStatus($userId, $sessionId,$data)
    {
        return $this->clientService->getTransactionStatus($userId, $sessionId, $data);
    }
    
    /**
     *  Encrypt mathod.
     *
     * 
     */
    public function encryptData($data)
    {
        $response = $this->clientService->encryptData();
		$success = openssl_public_encrypt($data, $crypted, $response);
		$encrypted = '';

		if( $success ) {
			$encrypted = base64_encode($crypted);
		}
		return $encrypted;
    }
}
