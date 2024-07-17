<?php

namespace App\Services;

use App\Http\Resources\PricingBand\PricingBand as PricingBandResource;
use App\Imports\PricingBandSeatsImport;
use App\Repositories\PricingBandRepository;
use App\Repositories\StadiumBlockRepository;
use App\Models\StadiumGeneralSetting;
use App\Models\PricingBand;
use App\Models\Match;
use Excel;
use Storage;

//use Maatwebsite\Excel\HeadingRowImport;

/**
 * User class to handle operator interactions.
 */
class PricingBandService
{
    /**
     * The pricing band repository instance.
     *
     * @var pricingBandRepository
     */
    private $pricingBandRepository;

    /**
     * The stadium block repository instance.
     *
     * @var stadiumBlockRepository
     */
    private $stadiumBlockRepository;

    /**
     * @var predefined image path
     */
    protected $filePath;

    /**
     * Create a new service instance.
     *
     * @param PricingBandRepository $pricingBandRepository
     */
    public function __construct(PricingBandRepository $pricingBandRepository, StadiumBlockRepository $stadiumBlockRepository)
    {
        $this->pricingBandRepository = $pricingBandRepository;
        $this->stadiumBlockRepository = $stadiumBlockRepository;
        $this->filePath = config('fanslive.IMAGEPATH.pricing_band_seats');
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->pricingBandRepository);
        unset($this->filePath);
    }

    /**
     * Handle logic to create a pricing band.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $excel = "";
        if (isset($data['seat'])) {
            $excel = $data['seat'];
            $response = $this->checkFileHeader($excel);
            if(!$response) {
                return $response;
            }
            $seatFile = uploadImageToS3($data['seat'], $this->filePath);
        } else {
            $seatFile['url'] = null;
            $seatFile['file_name'] = null;
        }
        $data['seat'] = $seatFile['url'];
        $data['seat_file_name'] = $seatFile['file_name'];
        $pricingBand = $this->pricingBandRepository->create($clubId, $user, $data);

        if ($excel != "") {
            Excel::import(new PricingBandSeatsImport($clubId, $pricingBand->id), $excel);
        }

        return $pricingBand;
    }

    /**
     * Handle logic to update a given pricing band.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function update($user, $pricingBand, $data)
    {
        $excel = "";
        if (isset($data['seat'])) {
            $excel = $data['seat'];
            $response = $this->checkFileHeader($excel);
            if(!$response) {
                return $response;
            }

            $existingLogo = $this->filePath.$pricingBand->seat_file_name;
            $disk = Storage::disk('s3');
            $disk->delete($existingLogo);

            $filePath = uploadImageToS3($data['seat'], $this->filePath);
        } else {
            $filePath['url'] = $pricingBand->seat;
            $filePath['file_name'] = $pricingBand->seat_file_name;
        }
        $data['seat'] = $filePath['url'];
        $data['seat_file_name'] = $filePath['file_name'];

        $pricingBandToUpdate = $this->pricingBandRepository->update($user, $pricingBand, $data);

        if ($excel != "") {
            $this->pricingBandRepository->deletePricingBandSeat($pricingBand);
            Excel::import(new PricingBandSeatsImport($pricingBand->club_id, $pricingBand->id), $excel);
        }

        return $pricingBandToUpdate;
    }

    /**
     * Handle logic to delete a given file.
     *
     * @param $pricingBand
     *
     * @return mixed
     */
    public function deleteFile($pricingBand)
    {
        $disk = Storage::disk('s3');
        $seatFile = $this->filePath.$pricingBand->seat_file_name;

        return $disk->delete($seatFile);
    }

    /**
     * Get pricing band user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $pricingBandData = $this->pricingBandRepository->getData($clubId, $data);

        return $pricingBandData;
    }

    public function checkFileHeader($excel)
    {
        $fileData = Excel::toArray('', $excel, null, \Maatwebsite\Excel\Excel::XLSX)[0];

        //Original File Header
        $originalHeader = 'Block,Row,Seat from,Seat to';

        $header = reset($fileData);
        foreach ($header as $key => $value) {
            $header[$key] = trim($value);
        }

        $header = implode(',',$header);
        $header = trim($header, ",");

        return $originalHeader == $header;
    }

    public function validateSeatData($data)
    {
        $response = $this->checkFileHeader($data['file']);
        if(!$response) {
            return $response;
        }
        $blockNameExcelArray=Excel::toArray('',$data['file'], null, \Maatwebsite\Excel\Excel::XLSX)[0];
        foreach ($blockNameExcelArray as $key => $value) {
            if($key>0){
                $resetBlockNameExcelArray[$key] = array_filter($blockNameExcelArray[$key]);
            }
        }
        $blockNameArray=array_unique(array_column($resetBlockNameExcelArray,0));
        $dbBlockNameArray=array_unique(array_column($this->stadiumBlockRepository->dbBlockNameArray(),'name'));
        $diffArray=array_diff($blockNameArray,$dbBlockNameArray);
        if(count($diffArray)>0)
        {
            return response()->json(['status'=>'error','block'=>$diffArray]);
        }
        return true;
    }

    public function getPricingBands($consumer, $data)
    {
    	$stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $consumer->club_id)->first();
    	$matchPricingBands = Match::find($data['match_id'])->ticketing->pricingBrand()->pluck('pricing_band_id')->toArray();
    	if($stadiumGeneralSetting->is_using_allocated_seating == 1) {
        	$pricingBands = PricingBand::selectRaw('pricing_bands.display_name,
       					(CASE
       						WHEN MIN(ROUND(price + (price * vat_rate / 100), 2)) != MAX(ROUND(price + (price * vat_rate / 100), 2)) THEN (CONCAT("£", MIN(ROUND(price + (price * vat_rate / 100), 2))," - £", MAX(ROUND(price + (price * vat_rate / 100), 2))))
       					ELSE
       						CONCAT("£", MIN(ROUND(price + (price * vat_rate / 100), 2)))
       					END) as display_pricing_range'
       					)
       					->where('club_id', $consumer->club_id)
       					->where('is_active', 1)
       					->whereIn('id', $matchPricingBands)
       					->groupBy('display_name')->get();
       		return response()->json(['data' => $pricingBands]);
        } else {
        	$pricingBands = PricingBand::where('club_id', $consumer->club_id)->whereIn('id', $matchPricingBands)->where('is_active', 1)->get();
        	return PricingBandResource::collection($pricingBands);
        }
    }
}
