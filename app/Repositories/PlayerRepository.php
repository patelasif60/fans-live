<?php

namespace App\Repositories;

use App\Models\Player;
use Illuminate\Support\Arr;

/**
 * Repository class for model.
 */
class PlayerRepository extends BaseRepository
{
    /**
     * Handle logic to create a player.
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $player = Player::create([
            'player_api_id' => Arr::get($data, 'player_api_id', null),
            'name'          => $data['name'],
        ]);

        return $player;
    }

    /**
     * Get a player.
     *
     * @param $playerId
     *
     * @return mixed
     */
    public function getPlayer($playerId)
    {
        $player = Player::where('player_api_id', $playerId)->first();

        return $player;
    }
}
