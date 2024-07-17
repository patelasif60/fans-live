<?php

namespace App\Services;

use App\Http\Resources\ConsumerCard\ConsumerCard as ConsumerCardResource;
use App\Repositories\ConsumerCardRepository;
use App\Services\ACIWorldWide\Client;
use Response;

/**
 * Consumer Card class to handle operator interactions.
 */
class ConsumerCardService
{
    /**
     * Create a ACIWorldWide client API variable.
     *
     * @return void
     */
    protected $apiclient;

    /**
     * The consumer card repository instance.
     *
     * @var ConsumerCardRepository
     */
    private $repository;

    /**
     * Create a new service instance.
     *
     * @param ConsumerCardRepository $consumerCardRepository
     */
    public function __construct(ConsumerCardRepository $consumerCardRepository, Client $apiclient)
    {
        $this->repository = $consumerCardRepository;
        $this->apiclient = $apiclient;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->repository);
        unset($this->apiclient);
    }

    /**
     * Process card details.
     *
     * @param $request
     *
     * @return mixed
     */
    public function processCardDetails($request, $user)
    {
        try {
            $registrationResponse = $this->apiclient->getRegistrationResult($request['checkout_id']);
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return Response::make(['error' => 'Bad request'], 400);
        }

        $consumerCard = $this->addCard($request, $registrationResponse, $user);

        return new ConsumerCardResource($consumerCard);
    }

    /**
     * Handle logic to add consumer card.
     *
     * @param $data
     * @param $registrationResponse
     *
     * @return mixed
     */
    public function addCard($data, $registrationResponse, $user)
    {
        $consumerCard = $this->repository->create($data, $registrationResponse, $user);

        return $consumerCard;
    }

    /**
     * Remove card.
     *
     * @param $consumerCardToken
     *
     * @return mixed
     */
    public function removeCard($consumerCard)
    {
        try {
            $registrationResponse = $this->apiclient->removeCard($consumerCard->token);
            $consumerCard->delete();

            return response()->json([
                'message' => 'Card has been removed successfully.',
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return Response::make(['error' => 'Bad request'], 400);
        }
    }
}
