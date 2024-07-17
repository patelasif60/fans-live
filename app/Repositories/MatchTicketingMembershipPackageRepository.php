<?php

namespace App\Repositories;

use App\Models\MatchTicketingMembershipPackage;
use Carbon\Carbon;

class MatchTicketingMembershipPackageRepository extends BaseRepository
{
    public function create($matchId, $data, $clubTimezone)
    {
        $matchTicketingMembershipPackage = null;
        foreach ($data as $value) {
            foreach ($value as $key=>$val) {
                if ($val) {
                    $matchTicketingMembershipPackage = MatchTicketingMembershipPackage::create([
                        'match_id'              => $matchId,
                        'membership_package_id' => $key,
                        'date'                  => convertDateTimezone($val, $clubTimezone, null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
                    ]);
                }
            }
        }

        return $matchTicketingMembershipPackage;
    }

    public function update($matchId, $data, $clubTimezone)
    {
        $matchTicketingMembershipPackage = null;
        foreach ($data as $key=>$val) {
            foreach ($val as $id=>$date) {
                if ($date) {
                    $dbFields = [
                        'match_id'              => $matchId,
                        'membership_package_id' => $key,
                        'date'                  => convertDateTimezone($date, $clubTimezone, null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
                    ];
                    if ($id > 0) {
                        $matchTicketingMembershipPackage = MatchTicketingMembershipPackage::where('id', $id)->update($dbFields);
                    } else {
                        $matchTicketingMembershipPackage = MatchTicketingMembershipPackage::create($dbFields);
                        $matchTicketingMembershipPackage->save();
                    }
                }
                else{
                    MatchTicketingMembershipPackage::where('id', $id)->delete();               
                }
            }
        }

        return $matchTicketingMembershipPackage;
    }

    public function delete($matchId)
    {
        return MatchTicketingMembershipPackage::where('match_id', $matchId)->delete();
    }
}
