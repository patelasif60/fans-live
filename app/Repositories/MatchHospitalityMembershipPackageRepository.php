<?php

namespace App\Repositories;

use App\Models\MatchHospitalityMembershipPackage;
use Carbon\Carbon;

class MatchHospitalityMembershipPackageRepository extends BaseRepository
{
    public function create($matchId, $data, $clubTimezone)
    {
        $matchHospitalityMembershipPackage = null;
        foreach ($data as $value) {
            foreach ($value as $key=>$val) {
                if ($val) {
                    $matchHospitalityMembershipPackage = MatchHospitalityMembershipPackage::create([
                        'match_id'              => $matchId,
                        'membership_package_id' => $key,
                        'date'                  => convertDateTimezone($val, $clubTimezone, null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
                    ]);
                }
            }
        }

        return $matchHospitalityMembershipPackage;
    }

    public function update($matchId, $data, $clubTimezone)
    {
        $matchHospitalityMembershipPackage = null;
        foreach ($data as $key=>$val) {
            foreach ($val as $id=>$date) {
                if ($date) {
                    $dbFields = [
                        'match_id'              => $matchId,
                        'membership_package_id' => $key,
                        'date'                  => convertDateTimezone($date, $clubTimezone, null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
                    ];
                    if ($id > 0) {
                        $matchHospitalityMembershipPackage = MatchHospitalityMembershipPackage::where('id', $id)->update($dbFields);
                    } else {
                        $matchHospitalityMembershipPackage = MatchHospitalityMembershipPackage::create($dbFields);
                        $matchHospitalityMembershipPackage->save();
                    }
                }
                else{
                    MatchHospitalityMembershipPackage::where('id', $id)->delete();               
                }
            }
        }

        return $matchHospitalityMembershipPackage;
    }

    public function delete($matchId)
    {
        return MatchHospitalityMembershipPackage::where('match_id', $matchId)->delete();
    }
}
