<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ConsumerCard\RemoveRequest;
use App\Http\Requests\Api\ConsumerCard\StoreRequest;
use App\Http\Resources\ConsumerCard\ConsumerCard as ConsumerCardResource;
use App\Models\Consumer;
use App\Models\ConsumerCard;
use App\Services\ACIWorldWide\Client;
use App\Services\ConsumerCardService;
use Illuminate\Http\Request;
use JWTAuth;
use Response;

/**
 * @group Payment method - Card
 *
 * APIs for managing consumer cards.
 */
class ConsumerCardController extends BaseController
{
    /**
     * Create a ACIWorldWide client API variable.
     *
     * @return void
     */
    protected $apiclient;

    /**
     * Create a Consumer card service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Client $apiclient, ConsumerCardService $consumerCardService)
    {
        $this->apiclient = $apiclient;
        $this->service = $consumerCardService;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->apiclient);
        unset($this->service);
    }

    /**
     * Get checkout id
     * Get checkout id for add card.
     *
     * @return void
     */
    public function getCheckoutIdForAddCard()
    {
        $params = [
            'amount'             => 92.00,
            'createRegistration' => 'true',
            'currency'           => 'EUR', // $currency
            'shopperResultUrl'   => 'app://'.config('app.domain').'/url',
            'notificationUrl'    => config('app.url').'/api/registration_notification',
        ];

        try {
            $response = $this->apiclient->createCheckout($params);
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return Response::make(['error' => 'Bad request'], 400);
        }

        return response()->json([
            'result' => $response,
        ]);
    }

    /**
     * Add card
     * Add card.
     *
     * @bodyParam checkout_id string required The checkout id of the registration. Example: abc
     * @bodyParam card_type string required The card type of the card. Example: Mastercard
     * @bodyParam truncated_pan string required The truncated pan of the card. Example: 2138
     * @bodyParam postcode string required A postcode. Example: 123456
     *
     * @return void
     */
    public function addCard(StoreRequest $request)
    {
        $request = $request->all();
        $user = JWTAuth::user();

        return $this->service->processCardDetails($request, $user);
    }

    /**
     * Remove card
     * Remove card.
     *
     * @bodyParam id int required An id of a card. Example: 1
     *
     * @return void
     */
    public function removeCard(RemoveRequest $request)
    {
        $request = $request->all();
        $consumerCard = ConsumerCard::where('id', $request['id'])->first();

        return $this->service->removeCard($consumerCard);
    }

    /**
     * Add card in registration
     * Add card in registration.
     *
     * @bodyParam checkout_id string required The checkout id of the registration. Example: abc
     * @bodyParam card_type string required The card type of the card. Example: Mastercard
     * @bodyParam truncated_pan string required The truncated pan of the card. Example: 2138
     *
     * @return void
     */
    public function addCardInRegistration(StoreRequest $request)
    {
        $request = $request->all();
        $user = null;

        return $this->service->processCardDetails($request, $user);
    }

    /**
     * List cards
     * List cards.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function listCards(Request $request)
    {
        $user = JWTAuth::user();
        $consumer = Consumer::with('cards')->where('user_id', $user->id)->first();

        if (!$consumer) {
            return response(['errors' => ['No consumer found.']], 401);
        }

        return ConsumerCardResource::collection($consumer->cards);
    }

    /**
     * Registration notification.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function registrationNotification(Request $request)
    {
        return response(['status' => 'success']);
    }
}
