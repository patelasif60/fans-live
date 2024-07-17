<?php

/*
 * This file is part of Instagram.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Vendor\Vinkla\Instagram\src;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Vinkla\Instagram\Instagram;

/**
 * This is the instagram class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class CustomInstagram extends Instagram
{
    /**
     * The access token.
     *
     * @var string
     */
    protected $accessToken;

    /**
     * The http client.
     *
     * @var \Http\Client\HttpClient
     */
    protected $httpClient;

    /**
     * The http request factory.
     *
     * @var \Http\Message\RequestFactory
     */
    protected $requestFactory;

    /**
     * Minimum Id.
     */
    protected $minId;

    /**
     * Create a new instagram instance.
     *
     * @param string                            $accessToken
     * @param \Http\Client\HttpClient|null      $httpClient
     * @param \Http\Message\RequestFactory|null $requestFactory
     *
     * @return void
     */
    public function __construct(string $accessToken, HttpClient $httpClient = null, RequestFactory $requestFactory = null, $minId = null)
    {
        $this->minId = $minId;
        parent::__construct($accessToken, $httpClient, $requestFactory);
    }

    /**
     * Fetch the media items.
     *
     * @throws \Vinkla\Instagram\InstagramException
     *
     * @return array
     */
    public function get(string $path, array $parameters = []): object
    {
        $uri = $this->buildApiUrl($path, $parameters);
        $request = $this->requestFactory->createRequest('GET', $uri);

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() === 400) {
            $body = json_decode((string) $response->getBody());

            throw new InstagramException($body->meta->error_message);
        }

        return json_decode((string) $response->getBody())->data;
    }
}
