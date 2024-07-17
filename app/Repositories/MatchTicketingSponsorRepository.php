<?php

namespace App\Repositories;

use App\Models\MatchTicketingSponsor;

/**
 * Repository class for model.
 */
class MatchTicketingSponsorRepository extends BaseRepository
{
    /**
     * Handle logic to create a new match ticketing sponsor.
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $matchTicketingSponsor = MatchTicketingSponsor::create([
            'match_ticketing_id' => $data['match_ticketing_id'],
            'logo'               => $data['logo'],
            'logo_file_name'     => $data['logo_file_name'],
        ]);

        return $matchTicketingSponsor;
    }

    public function update($data, $id)
    {
        $dbFields = [
            'match_ticketing_id' => $data['match_ticketing_id'],
            'logo'               => $data['logo'],
            'logo_file_name'     => $data['logo_file_name'],
        ];
        $matchTicketingSponsor = MatchTicketingSponsor::where('id', $id)->update($dbFields);

        return $matchTicketingSponsor;
    }

    public function delete($data, $matchTicketingId)
    {
        $matchTicketingSponsor = MatchTicketingSponsor::where('match_ticketing_id', $matchTicketingId)->whereNotIn('id', array_filter(array_column($data, 'sponserId')))->delete();
    }

    public function deleteLogo($data, $sponserId, $matchTicketingId)
    {
        $result = array_diff(array_filter(array_column($data, 'sponserId')), $sponserId);

        return MatchTicketingSponsor::where('match_ticketing_id', $matchTicketingId)->whereNotIn('id', $result)->select('id', 'logo_file_name')->get();
    }
    public function allDelete($matchTicketingId)
    {
        $matchTicketingSponsor = MatchTicketingSponsor::where('match_ticketing_id', $matchTicketingId)->delete();
    }
}
