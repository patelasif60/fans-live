<?php

namespace App\Services\PaymentIQ;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Str;

/**
 * Client to make API calls to the ACI Worldwide payment.
 */
class Client extends HttpClient
{
    protected $baseUrl;

    protected $merchantId;

    /**
     * Create a new service instance.
     *
     * @param ConsumerRepository $repository
     */
    public function __construct()
    {
        $this->baseUrl = config('payments.paymentiq.base_url');
        $this->merchantId = config('payments.paymentiq.merchant_id');

        parent::__construct([
            'base_uri' => Str::finish($this->baseUrl, '/'),
            'verify'   => config('app.env') == 'production' ? true : false,
        ]);
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->baseUrl);
        unset($this->merchantId);
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
        $response = $this->post($provider . '/' . $type . '/' . $method, [
            'form_params' => $data,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get user's payment accounts.
     *
     * @param $userId
     * @param $data
     */
    public function getUsersPaymentAccount($userId, $data)
    {
        $response = $this->get('user/account/' . $this->merchantId . '/' . $userId, [
            'query' => $data,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Delete user's payment account.
     *
     * @param $userId
     * @param $accountId
     * @param $data
     */
    public function deleteUsersPaymentAccount($userId, $accountId, $data)
    {
        $response = $this->delete('user/account/' . $this->merchantId . '/' . $userId . '/' . $accountId, [
            'query' => $data,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get available payment methods based on the locale (i18n).
     *
     * @param $data
     */
    public function getPaymentMethodsByLocale($data)
    {
        $response = $this->get('user/payment/method/' . $this->merchantId, [
            'query' => $data,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get user's available payment methods.
     *
     * @param $userId
     * @param $data
     */
    public function getUsersPaymentMethods($userId, $data)
    {
        $response = $this->get('user/payment/method/' . $this->merchantId . '/' . $userId, [
            'query' => $data,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get user's ongoing transactions.
     *
     * @param $userId
     * @param $data
     */
    public function getUsersOngoingTransactions($userId, $data)
    {
        $response = $this->get('user/transaction/' . $this->merchantId . '/' . $userId, [
            'query' => $data,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Cancel user's withdrawal.
     *
     * @param $userId
     * @param $transactionId
     * @param $data
     */
    public function cancelTransaction($userId, $transactionId, $data)
    {
        $response = $this->delete('user/transaction/' . $this->merchantId . '/' . $userId . '/' . $transactionId, [
            'query' => $data,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get transaction status.
     *
     * @param $userId
     * @param $transactionId
     * @param $data
     */
    public function getTransactionStatus($userId, $transactionId, $data)
    {
        $response = $this->get('user/transaction/' . $this->merchantId . '/' . $userId . '/' . $transactionId . '/status', [
            'query' => $data,
        ]);

        return json_decode((string) $response->getBody(), true);
    }
     /**
     *  Encrypt mathod.
     *
     * 
     */
    public function encryptData()
    {
         $response = $this->get('viq/getvaultiqpublickey/' . $this->merchantId );

        return json_decode((string) $response->getBody(), true);
    }
}
