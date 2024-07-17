<?php

namespace App\Services;

use App\Imports\TicketingUnavailableSeatsImport;
use App\Models\MatchTicketing;
use App\Models\MatchTicketingSponsor;
use App\Repositories\MatchTicketingMembershipPackageRepository;
use App\Repositories\MatchTicketingRepository;
use App\Repositories\MatchTicketingSponsorRepository;
use Excel;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\HeadingRowImport;
use Storage;

/**
 * User class to handle match ticketing interactions.
 */
class MatchTicketingService
{
    /**
     * The match ticketing repository instance.
     *
     * @var matchTicketingRepository
     */
    private $matchTicketingRepository;

    /**
     * @var predefined image path
     */
    protected $imagePath;

    /**
     * @var predefined logo path
     */
    protected $logoPath;

    /**
     * The match ticketing service instance.
     *
     * @var matchTicketingSponsorRepository
     */
    protected $matchTicketingSponsorRepository;

    /**
     * The match ticketing membership instance.
     *
     * @var matchTicketingMembershipPackageRepository
     */
    protected $MatchTicketingMembershipPackageRepository;

    /**
     * Create a new service instance.
     *
     * @param MatchTicketingRepository $matchTicketingRepository
     */
    public function __construct(MatchTicketingRepository $matchTicketingRepository, MatchTicketingSponsorRepository $matchTicketingSponsorRepository, MatchTicketingMembershipPackageRepository $MatchTicketingMembershipPackageRepository)
    {
        $this->matchTicketingRepository = $matchTicketingRepository;
        $this->imagePath = config('fanslive.IMAGEPATH.match_ticketing_unavailable_seats');
        $this->matchTicketingSponsorRepository = $matchTicketingSponsorRepository;
        $this->matchTicketingMembershipPackageRepository = $MatchTicketingMembershipPackageRepository;
        $this->logoPath = config('fanslive.IMAGEPATH.match_ticketing_sponsor_logo');
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->matchTicketingRepository);
        unset($this->imagePath);
        unset($this->matchTicketingSponsorRepository);
        unset($this->logoPath);
    }

    /**
     * Handle logic to create a match ticketing.
     *
     * @param $matchId
     * @param $data
     *
     * @return mixed
     */
    public function create($matchId, $data)
    {
        $matchTicketingData['match_id'] = $matchId;
        $matchTicketingData['maximum_ticket_per_user'] = Arr::get($data, 'maximum_number_of_ticket_per_user');
        $excel = '';
        if (Arr::get($data, 'unavailable_seats')) {
            $excel = $data['unavailable_seats'];
            $upload = uploadImageToS3($data['unavailable_seats'], $this->imagePath);
            $matchTicketingData['unavailable_seats'] = Arr::get($upload, 'url');
            $matchTicketingData['unavailable_seats_file_name'] = Arr::get($upload, 'file_name');
        }
        $matchTicketingData['rewards_percentage_override'] = Arr::get($data, 'rewards_percentage_override', null);
        $matchTicketingData['allow_ticket_returns_resales'] = Arr::get($data, 'allow_ticket_returns_resales', 0);
        $matchTicketingData['ticket_resale_fee_type'] = Arr::has($data, 'allow_ticket_returns_resales') ? Arr::get($data, 'ticket_resale_fee_type') : null;
        $matchTicketingData['ticket_resale_fee_amount'] = Arr::has($data, 'allow_ticket_returns_resales') ? Arr::get($data, 'ticket_resale_fee_amount') : null;
        $matchTicketing = $this->matchTicketingRepository->create($matchId, $matchTicketingData);

        if ($excel) {
            $this->importExcel($matchTicketing->id, $excel);
        }

        if (Arr::get($data, 'package')) {
            $this->matchTicketingMembershipPackageRepository->create($matchId, $data['package'], $data['global_club_timezone']);
        }
        if(isset($data['available_blocks'])){
            foreach ($data['available_blocks'] as $block) {
                $this->matchTicketingRepository->createAvailableBlock($matchTicketing->id, $block);
            }
        }

        foreach ($data['pricing_bands'] as $band) {
            $this->matchTicketingRepository->createPricingBand($matchTicketing->id, $band);
        }

        if (Arr::get($data, 'sponsors')) {
            foreach ($data['sponsors'] as $sponsor) {
                $matchSponsorData['match_ticketing_id'] = $matchTicketing->id;
                $upload = uploadImageToS3($sponsor['sponsor'], $this->logoPath);
                $matchSponsorData['logo'] = Arr::get($upload, 'url');
                $matchSponsorData['logo_file_name'] = Arr::get($upload, 'file_name');
                $this->matchTicketingSponsorRepository->create($matchSponsorData);
            }
        }

        return $matchTicketing;
    }

    /**
     * Handle logic to import excel.
     *
     * @param $matchTicketingId
     * @param $excel
     *
     * @return mixed
     */
    public function importExcel($matchTicketingId, $excel)
    {
        /*$headings = (new HeadingRowImport)->toArray($excel);
        if(count(Arr::flatten($headings)) == 4 && in_array('block', Arr::flatten($headings)) && in_array('row',Arr::flatten($headings)) && in_array('seat_from',Arr::flatten($headings)) && in_array('seat_to',Arr::flatten($headings))){*/
        Excel::import(new TicketingUnavailableSeatsImport($matchTicketingId), $excel);
        /*} else {
            return false;
        }*/
    }

    /**
     * Handle logic to update a match ticketing.
     *
     * @param $matchId
     * @param $data
     *
     * @return mixed
     */
    public function update($matchId, $data)
    {
        $matchTicketing = MatchTicketing::where('match_id', $matchId)->first();

        $matchTicketingData['maximum_ticket_per_user'] = Arr::get($data, 'maximum_number_of_ticket_per_user');
        $excel = '';
        if (Arr::get($data, 'unavailable_seats')) {
            if ($matchTicketing) {
                $existingLogo = $this->imagePath.$matchTicketing->unavailable_seats_file_name;
                $disk = Storage::disk('s3');
                $disk->delete($existingLogo);
            }
            $excel = $data['unavailable_seats'];
            $upload = uploadImageToS3($data['unavailable_seats'], $this->imagePath);
        } else {
            if (Arr::get($matchTicketing, 'unavailable_seats')) {
                $upload['url'] = $matchTicketing->unavailable_seats;
                $upload['file_name'] = $matchTicketing->unavailable_seats_file_name;
            } else {
                $upload['url'] = null;
                $upload['file_name'] = null;
            }
        }
        $matchTicketingData['unavailable_seats'] = Arr::get($upload, 'url');
        $matchTicketingData['unavailable_seats_file_name'] = Arr::get($upload, 'file_name');
        $matchTicketingData['rewards_percentage_override'] = Arr::get($data, 'rewards_percentage_override', null);
        $matchTicketingData['allow_ticket_returns_resales'] = Arr::get($data, 'allow_ticket_returns_resales', 0);
        $matchTicketingData['ticket_resale_fee_type'] = $matchTicketingData['allow_ticket_returns_resales'] == 1 ? Arr::get($data, 'ticket_resale_fee_type') : null;
        $matchTicketingData['ticket_resale_fee_amount'] = $matchTicketingData['allow_ticket_returns_resales'] == 1 ? Arr::get($data, 'ticket_resale_fee_amount') : null;
        $matchTicketingToUpdate = $this->matchTicketingRepository->update($matchId, $matchTicketingData);

        if ($excel) {
            $this->importExcel($matchTicketing->id, $excel);
        }

        if (Arr::get($data, 'package')) {
            $this->matchTicketingMembershipPackageRepository->update($matchId, $data['package'], $data['global_club_timezone']);
        }

        if (Arr::get($matchTicketing, 'id')) {
            $ticketId = $matchTicketing->id;
        } else {
            $ticketId = $matchTicketingToUpdate->id;
        }
        $this->matchTicketingRepository->deleteBlock($ticketId);
        $this->matchTicketingRepository->deleteBand($ticketId);

        if(isset($data['available_blocks'])){
            foreach ($data['available_blocks'] as $block) {
                $this->matchTicketingRepository->createAvailableBlock($ticketId, $block);
            }
        }

        foreach ($data['pricing_bands'] as $band) {
            $this->matchTicketingRepository->createPricingBand($ticketId, $band);
        }
        if(isset($data['sponsors'])){
        $sponserId = [];
        foreach ($data['sponsors'] as $sponsor) {
            if (isset($sponsor['sponsor'])) {
                if ($sponsor['sponsor']) {
                    $sponserId[] = $sponsor['sponserId'];
                }
            }
        }
        $logoDetail = $this->matchTicketingSponsorRepository->deleteLogo($data['sponsors'], $sponserId, $ticketId);
        if ($logoDetail) {
            foreach ($logoDetail->pluck('logo_file_name')->toArray() as $key=>$val) {
                $disk = Storage::disk('s3');
                $existingLogo = $this->logoPath.$val;
                $disk->delete($existingLogo);
            }
        }
        $this->matchTicketingSponsorRepository->delete($data['sponsors'], $ticketId);
        foreach ($data['sponsors'] as $sponsor) {
            if (isset($sponsor['sponsor'])) {
                if ($sponsor['sponsor']) {
                    $matchSponsorData['match_ticketing_id'] = $ticketId;
                    $upload = uploadImageToS3($sponsor['sponsor'], $this->logoPath);
                    $matchSponsorData['logo'] = Arr::get($upload, 'url');
                    $matchSponsorData['logo_file_name'] = Arr::get($upload, 'file_name');
                    if ($sponsor['sponserId'] > 0) {
                        $this->matchTicketingSponsorRepository->update($matchSponsorData, $sponsor['sponserId']);
                    } else {
                        $this->matchTicketingSponsorRepository->create($matchSponsorData);
                    }
                }
            }
        }}
        else{
            $this->matchTicketingSponsorRepository->allDelete($ticketId);
        }

        return $matchTicketingToUpdate;
    }

    /**
     * Handle logic to delete a match ticketing.
     *
     * @param $matchId
     *
     * @return mixed
     */
    public function delete($matchId)
    {
        /*$matchTicketing = MatchTicketing::where('match_id', $matchId)->first();
    	$logo = $this->imagePath.$matchTicketing->unavailable_seats_file_name;
        $disk = Storage::disk('s3');
        $disk->delete($logo);*/
        $this->deleteImage($matchId);

        $matchTicketing = $this->matchTicketingRepository->delete($matchId);
        $this->matchTicketingMembershipPackageRepository->delete($matchId);

        return $matchTicketing;
    }

    /**
     * Handle logic to delete a given image file.
     *
     * @param $matchId
     *
     * @return mixed
     */
    public function deleteImage($matchId)
    {
        $matchTicketing = MatchTicketing::where('match_id', $matchId)->first();
        if (isset($matchTicketing->unavailable_seats_file_name)) {
            $image = $this->imagePath.$matchTicketing->unavailable_seats_file_name;
            $disk = Storage::disk('s3');
            $disk->delete($image);

            $MatchTicketingSponsors = MatchTicketingSponsor::where('match_ticketing_id', $matchTicketing->id)->get();
            foreach ($MatchTicketingSponsors as $sponsor) {
                if(isset($sponsor->logo_file_name))
                {
                    $logo = $this->logoPath.$sponsor->logo_file_name;
                    $disk->delete($logo);   
                }
            }
        }
        /*$matchTicketingImages =  $this->matchTicketingService->deleteImage($matchId);
        return $matchTicketingImages;*/
        /*$disk = Storage::disk('s3');
        $image = $this->imagePath . $cta->image_file_name;
        return $disk->delete($image);*/
    }
}
