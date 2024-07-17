<?php

namespace App\Repositories;

use App\Models\MatchTicketing;
use App\Models\MatchTicketingAvailableBlock;
use App\Models\MatchTicketingPricingBand;

/**
 * Repository class for model.
 */
class MatchTicketingRepository extends BaseRepository
{
    /**
     * Handle logic to create a new match ticketing.
     *
     * @param $matchId
     * @param $data
     *
     * @return mixed
     */
    public function create($matchId, $data)
    {
        $matchTicketing = MatchTicketing::create([
            'match_id'                     => $matchId,
            'maximum_ticket_per_user'      => $data['maximum_ticket_per_user'],
            'unavailable_seats'            => isset($data['unavailable_seats']) ? $data['unavailable_seats'] : null,
            'unavailable_seats_file_name'  => isset($data['unavailable_seats_file_name']) ? $data['unavailable_seats_file_name'] : null,
            'rewards_percentage_override'  => $data['rewards_percentage_override'],
            'allow_ticket_returns_resales' => $data['allow_ticket_returns_resales'],
            'ticket_resale_fee_type'       => $data['ticket_resale_fee_type'],
            'ticket_resale_fee_amount'     => $data['ticket_resale_fee_amount'],
        ]);

        return $matchTicketing;
    }

    /**
     * Handle logic to create a new match ticketing.
     *
     * @param $matchId
     * @param $data
     *
     * @return mixed
     */
    public function update($matchId, $data)
    {
        $matchTicketing = MatchTicketing::where('match_id', $matchId)->first();
        if ($matchTicketing) {
            $matchTicketing->fill([
                'maximum_ticket_per_user'      => $data['maximum_ticket_per_user'],
                'unavailable_seats'            => isset($data['unavailable_seats']) ? $data['unavailable_seats'] : $matchTicketing->unavailable_seats,
                'unavailable_seats_file_name'  => isset($data['unavailable_seats_file_name']) ? $data['unavailable_seats_file_name'] : $matchTicketing->unavailable_seats_file_name,
                'rewards_percentage_override'  => $data['rewards_percentage_override'],
                'allow_ticket_returns_resales' => $data['allow_ticket_returns_resales'],
                'ticket_resale_fee_type'       => $data['ticket_resale_fee_type'],
                'ticket_resale_fee_amount'     => $data['ticket_resale_fee_amount'],
            ]);
            $matchTicketing->save();
        } else {
            $matchTicketing = $this->create($matchId, $data);
        }

        return $matchTicketing;
    }

    /**
     * Handle logic to delete match ticketing.
     *
     * @param $matchId
     *
     * @return mixed
     */
    public function delete($matchId)
    {
        $matchTicketing = MatchTicketing::where('match_id', $matchId)->first();
        if ($matchTicketing) {
            return $matchTicketing->delete();
        }
    }

    /**
     * Handle logic to create a new match ticketing available block.
     *
     * @param $matchTicketingId
     * @param $blockId
     *
     * @return mixed
     */
    public function createAvailableBlock($matchTicketingId, $blockId)
    {
        $matchTicketingAvailableBlock = MatchTicketingAvailableBlock::create([
            'match_ticketing_id' => $matchTicketingId,
            'block_id'           => $blockId,
        ]);

        return $matchTicketingAvailableBlock;
    }

    /**
     * Handle logic to create a new match ticketing pricing band.
     *
     * @param $matchTicketingId
     * @param $bandId
     *
     * @return mixed
     */
    public function createPricingBand($matchTicketingId, $bandId)
    {
        $matchTicketingPricingBand = MatchTicketingPricingBand::create([
            'match_ticketing_id' => $matchTicketingId,
            'pricing_band_id'    => $bandId,
        ]);

        return $matchTicketingPricingBand;
    }

    public function deleteBlock($matchTicketingId)
    {
        $matchTicketingAvailableBlock = MatchTicketingAvailableBlock::where('match_ticketing_id', $matchTicketingId)->delete();
    }

    public function deleteBand($matchTicketingId)
    {
        $matchTicketingAvailableBlock = MatchTicketingPricingBand::where('match_ticketing_id', $matchTicketingId)->delete();
    }
}
