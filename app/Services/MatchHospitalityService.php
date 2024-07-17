<?php

namespace App\Services;

use App\Imports\HospitalityUnavailableSeatsImport;
use App\Models\MatchHospitality;
use App\Repositories\MatchHospitalityMembershipPackageRepository;
use App\Repositories\MatchHospitalityRepository;
use Excel;
use Illuminate\Support\Arr;
use Storage;

/**
 * User class to handle operator interactions.
 */
class MatchHospitalityService
{
    /**
     * The match event repository instance.
     *
     * @var repository
     */
    protected $matchHospitalityRepository;

    protected $imagePath;

    /**
     * Create a new service instance.
     *
     * @param MatchHospitalityRepository $MatchHospitalityRepository
     */
    public function __construct(MatchHospitalityRepository $matchHospitalityRepository, MatchHospitalityMembershipPackageRepository $MatchHospitalityMembershipPackageRepository)
    {
        $this->imagePath = config('fanslive.IMAGEPATH.match_hospitality_unavailable_seats');
        $this->matchHospitalityRepository = $matchHospitalityRepository;
        $this->matchHospitalityMembershipPackageRepository = $MatchHospitalityMembershipPackageRepository;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->matchHospitalityRepository);
    }

    /**
     * Handle logic to create a membership package.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($matchId, $requestData)
    {
        $data['match_id'] = $matchId;
        $excel = '';
        $data['rewards_percentage_override'] = Arr::get($requestData, 'hospitality_rewards_percentage_override', null);
        $matchHospitality = $this->matchHospitalityRepository->create($data);

        if ($excel) {
            $this->importExcel($matchHospitality->id, $excel);
        }

        if (Arr::get($requestData, 'hospitality_package')) {
            $this->matchHospitalityMembershipPackageRepository->create($matchId, $requestData['hospitality_package'], $requestData['global_club_timezone']);
        }

        foreach ($requestData['hospitality_suites'] as $suite) {
            $this->matchHospitalityRepository->createHospitalitySuite($matchHospitality->id, $suite);
        }
    }

    /**
     * Handle logic to import excel.
     *
     * @param $matchHospitalityId
     * @param $excel
     *
     * @return mixed
     */
    public function importExcel($matchHospitalityId, $excel)
    {
        //$headings = (new HeadingRowImport)->toArray($excel);
        // if(count(Arr::flatten($headings))==5 && in_array('block', Arr::flatten($headings)) && in_array('block',Arr::flatten($headings)) && in_array('row_from',Arr::flatten($headings)) && in_array('seat_from',Arr::flatten($headings)) && in_array('row_to',Arr::flatten($headings)) && in_array('seat_to',Arr::flatten($headings))){
        Excel::import(new HospitalityUnavailableSeatsImport($matchHospitalityId), $excel);
        // }
        // else{
        //  return false;
        // }
    }

    /**
     * Handle logic to update a given membership package.
     *
     * @param $user
     * @param $membershipPackage
     * @param $data
     *
     * @return mixed
     */
    public function update($matchId, $data)
    {
        $matchHospitality = MatchHospitality::where('match_id', $matchId)->first();
        $matchHospitalityData['match_id'] = $matchId;
        $excel = '';
        $matchHospitalityData['rewards_percentage_override'] = Arr::get($data, 'hospitality_rewards_percentage_override', null);

        $matchHospitalityToUpdate = $this->matchHospitalityRepository->update($matchHospitality, $matchHospitalityData);

        if ($excel) {
            $this->importExcel($matchHospitality->id, $excel);
        }

        if (Arr::get($data, 'hospitality_package')) {
            $this->matchHospitalityMembershipPackageRepository->update($matchId, $data['hospitality_package'], $data['global_club_timezone']);
        }

        $this->matchHospitalityRepository->deleteSuite($matchHospitality->id);
        foreach ($data['hospitality_suites'] as $suite) {
            $this->matchHospitalityRepository->createHospitalitySuite($matchHospitality->id, $suite);
        }

        return $matchHospitalityToUpdate;
    }

    /**
     * Handle logic to delete a match hospitality.
     *
     * @param $matchId
     *
     * @return mixed
     */
    public function delete($matchId)
    {
        $matchHospitality = MatchHospitality::where('match_id', $matchId)->first();
        if ($matchHospitality) {
            $matchHospitality = $this->matchHospitalityRepository->delete($matchHospitality->id);
            $this->matchHospitalityMembershipPackageRepository->delete($matchId);
            return $matchHospitality;
        }
    }

    public function manageUpload($data)
    {
        // if (Arr::get($data,'action_replay_video'))
        // {
        //     $disk = Storage::disk('s3');
        //     foreach($data['action_replay_video'] as $key => $video)
        //     {
        //         if(Arr::get($data,"event_edit_video_name.$key",0))
        //         { // if video already exist and replace on edit remove from s3 server
        //             $disk->delete($this->videoPath.Arr::get($data,"event_edit_video_name.$key"));
        //         }
        //         $data['action_replay_video'][$key] = uploadImageToS3($video,$this->videoPath); // upload video here
        //     }
        // }
    }

    /**
     * Get membership package user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        return $this->matchHospitalityRepository->getData($data);
    }
}
