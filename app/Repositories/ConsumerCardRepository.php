<?php

namespace App\Repositories;

use App\Models\Consumer;
use App\Models\ConsumerCard;
use Illuminate\Support\Arr;

/**
 * Repository class for Consumer Card model.
 */
class ConsumerCardRepository extends BaseRepository
{
    /**
     * Handle logic to create a new consumer card.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($data, $registrationResponse, $user)
    {
        $consumerId = null;
        \Log::info($user);
        if ($user) {
            $consumer = Consumer::where('user_id', $user->id)->first();
            $consumerId = $consumer->id;
        }
        \Log::info($consumerId);
        $consumerCard = ConsumerCard::create([
            'consumer_id'   => $consumerId,
            'token'         => $registrationResponse['id'],
            'brand'         => $data['card_type'],
            'truncated_pan' => $data['truncated_pan'],
            'postcode'      => Arr::get($data, 'postcode', null),
        ]);

        return $consumerCard;
    }
}
