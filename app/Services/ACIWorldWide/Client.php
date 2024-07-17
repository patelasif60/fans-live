<?php

namespace App\Services\ACIWorldWide;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Str;

/**
 * Client to make API calls to the ACI Worldwide payment.
 */
class Client extends HttpClient
{
    protected $baseUrl;

    protected $apiVersion;

    /**
     * Create a new service instance.
     *
     * @param ConsumerRepository $repository
     */
    public function __construct()
    {
        $this->baseUrl = config('payments.aciworldwide.base_url');
        $this->apiVersion = config('payments.aciworldwide.api_version');

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
        unset($this->apiVersion);
    }

    /**
     * Handle logic to create a checkout id.
     *
     * @param $options
     */
    public function createCheckout($options)
    {
        $defaults = $this->getDefaultCheckoutParams();
        $options = array_merge($defaults, $options);

        $response = $this->post($this->apiVersion.'/checkouts', [
            'form_params' => $options,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get status of payment.
     *
     * @param $id
     */
    public function getPaymentStatus($id)
    {
        $response = $this->get($this->apiVersion.'/checkouts/'.$id.'/payment', [
            'query' => $this->getDefaultCheckoutParams(),
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get registration result.
     *
     * @param $id
     */
    public function getRegistrationResult($id)
    {
        \Log::info($id);
        $response = $this->get($this->apiVersion.'/checkouts/'.$id.'/registration', [
            'query' => $this->getDefaultCheckoutParams(),
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get remove card result.
     *
     * @param $token
     */
    public function removeCard($token)
    {
        $defaults = $this->getDefaultCheckoutParams();

        $response = $this->delete($this->apiVersion.'/registrations/'.$token, [
            'query' => $this->getDefaultCheckoutParams(),
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Parse status code to payment status.
     *
     * @param $status
     */
    public function parsePaymentStatus($status)
    {
        if (preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $status)
            || preg_match("/^(000\.400\.0[^3]|000\.400\.100)/", $status)) {
            return 'Success';
        }

        if (preg_match("/^(000\.200)/", $status)
            || preg_match("/^(800\.400\.5|100\.400\.500)/", $status)) {
            return 'Pending';
        }

        if (preg_match("/^(000\.400\.[1][0-9][1-9]|000\.400\.2)/", $status)
            || preg_match("/^(800\.[17]00|800\.800\.[123])/", $status)
            || preg_match("/^(900\.[1234]00|000\.400\.030)/", $status)
            || preg_match("/^(800\.5|999\.|600\.1|800\.800\.8)/", $status)
            || preg_match("/^(100\.39[765])/", $status)
            || preg_match("/^(100\.400|100\.38|100\.370\.100|100\.370\.11)/", $status)
            || preg_match("/^(800\.400\.1)/", $status)
            || preg_match("/^(800\.400\.2|100\.380\.4|100\.390)/", $status)
            || preg_match("/^(100\.100\.701|800\.[32])/", $status)
            || preg_match("/^(800\.1[123456]0)/", $status)
            || preg_match("/^(600\.[23]|500\.[12]|800\.121)/", $status)
            || preg_match("/^(100\.[13]50)/", $status)
            || preg_match("/^(100\.250|100\.360)/", $status)
            || preg_match("/^(700\.[1345][05]0)/", $status)
            || preg_match("/^(200\.[123]|100\.[53][07]|800\.900|100\.[69]00\.500)/", $status)
            || preg_match("/^(100\.800)/", $status)
            || preg_match("/^(100\.[97]00)/", $status)
            || preg_match("/^(100\.100|100.2[01])/", $status)
            || preg_match("/^(100\.55)/", $status)
            || preg_match("/^(100\.380\.[23]|100\.380\.101)/", $status)) {
            return 'Rejected';
        }
        if (preg_match("/^(000\.100\.2)/", $status)) {
            return 'Chargeback';
        }
    }

    /**
     * Get default checkout params.
     */
    protected function getDefaultCheckoutParams()
    {
        return [
            'authentication.userId'   => $this->userId(),
            'authentication.password' => $this->password(),
            'authentication.entityId' => $this->entityId(),
        ];
    }

    /**
     * Get user id of ACI Worldwide.
     */
    protected function userId()
    {
        return config('payments.aciworldwide.user_id');
    }

    /**
     * Get password of ACI Worldwide.
     */
    protected function password()
    {
        return config('payments.aciworldwide.password');
    }

    /**
     * Get entity id of ACI Worldwide.
     */
    protected function entityId()
    {
        return config('payments.aciworldwide.entity_id');
    }
}
