<?php

namespace App\Services;

use App\Imports\StadiumBlockSeatsImport;
use App\Imports\StadiumBlockSeatsUpdate;
use App\Repositories\StadiumBlockRepository;
use App\Models\Consumer;
use App\Models\Match;
use App\Models\StadiumBlock;
use App\Models\StadiumGeneralSetting;
use App\Models\PricingBand;
use App\Models\TicketTransaction;
use App\Models\StadiumBlockSeat;
use App\Models\BookedTicket;
use App\Models\TicketingUnavailableSeat;
use App\Models\SellMatchTicket;
use Excel;
use function GuzzleHttp\json_decode;
use Storage;
USE DB;

/**
 * User class to handle operator interactions.
 */
class StadiumBlockService
{
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
     * @param StadiumBlockRepository $stadiumBlockRepository
     */
    public function __construct(StadiumBlockRepository $stadiumBlockRepository)
    {
        $this->stadiumBlockRepository = $stadiumBlockRepository;
        $this->filePath = config('fanslive.IMAGEPATH.stadium_block_seating_plan');
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->stadiumBlockRepository);
        unset($this->filePath);
    }

    /**
     * Handle logic to create a stadium block.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $seatFile['url'] = null;
        $seatFile['file_name'] = null;
        $excel = false;
        if (isset($data['seating_plan'])) {
            $excel = $data['seating_plan'];
            $response = $this->checkFileHeader($excel);
            if(!$response) {
                return $response;
            }
            $seatFile = uploadImageToS3($data['seating_plan'], $this->filePath);
        }
        $data['seating_plan'] = $seatFile['url'];
        $data['seating_plan_file_name'] = $seatFile['file_name'];
        $stadiumBlock = $this->stadiumBlockRepository->create($clubId, $user, $data);
        if ($excel) {
            Excel::import(new StadiumBlockSeatsImport($stadiumBlock->id), $excel);
        }

        return $stadiumBlock;
    }

    /**
     * Handle logic to update a given stadium block.
     *
     * @param $user
     * @param $stadiumBlock
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $stadiumBlock, $data)
    {
        $excel = false;
        if (isset($data['seating_plan'])) {
            $existingLogo = $this->filePath.$stadiumBlock->seating_plan_file_name;
            $disk = Storage::disk('s3');
            $disk->delete($existingLogo);
            $excel = $data['seating_plan'];
            $response = $this->checkFileHeader($excel);
            if(!$response) {
                return $response;
            }

            $filePath = uploadImageToS3($data['seating_plan'], $this->filePath);
        } else {
            $filePath['url'] = $stadiumBlock->seating_plan;
            $filePath['file_name'] = $stadiumBlock->seating_plan_file_name;
        }
        $data['seating_plan'] = $filePath['url'];
        $data['seating_plan_file_name'] = $filePath['file_name'];

        $stadiumBlockToUpdate = $this->stadiumBlockRepository->update($user, $stadiumBlock, $data);

        if ($excel) {
            Excel::import(new StadiumBlockSeatsUpdate($stadiumBlock->id), $excel);
        }

        return $stadiumBlockToUpdate;
    }

    /**
     * Handle logic to delete a given file.
     *
     * @param $stadiumBlock
     *
     * @return mixed
     */
    public function deleteFile($stadiumBlock)
    {
        $disk = Storage::disk('s3');
        $seatFile = $this->filePath.$stadiumBlock->seating_plan_file_name;

        return $disk->delete($seatFile);
    }

    /**
     * Get stadium block user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $stadiumBlockData = $this->stadiumBlockRepository->getData($clubId, $data);

        return $stadiumBlockData;
    }

    /**
     * Get block area.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getArea($clubId)
    {
        $stadiumBlocks = $this->stadiumBlockRepository->getActiveStadiumBlock($clubId);
        $data = [];
        foreach ($stadiumBlocks as $key => $block) {
            $data[$key]['type'] = null;
            $data[$key]['coords'] = null;
            if ($block->area !== null) {
                $area = json_decode($block->area, true);
                if ($area['type'] === 'rectangle') {
                    $data[$key]['type'] = 'rect';
                    $data[$key]['coords'] = $this->getRectangle($area);
                } elseif ($area['type'] === 'polygon') {
                    $data[$key]['type'] = 'poly';
                    $data[$key]['coords'] = $this->getPolygon($area);
                } else {
                    $data[$key]['type'] = 'circle';
                    $data[$key]['coords'] = $this->getCircle($area);
                }
            }
            $data[$key]['stadiumBlockId'] = $block->id;
            $data[$key]['stadiumBlockName'] = $block->name;
        }

        return $data;
    }

    /**
     * Get block getRectangle.
     *
     * @param $area
     *
     * @return mixed
     */
    public function getRectangle($area)
    {
        $width = $area['coords']['x'] + $area['coords']['width'];
        $height = $area['coords']['y'] + $area['coords']['height'];

        return $area['coords']['x'].','.$area['coords']['y'].','.$width.','.$height;
    }

    /**
     * Get block getPolygon.
     *
     * @param $area
     *
     * @return mixed
     */
    public function getPolygon($area)
    {
        foreach ($area['coords']['points'] as $coordVal) {
            $poly[] = $coordVal['x'];
            $poly[] = $coordVal['y'];
        }

        return implode(',', $poly);
    }

    /**
     * Get block getCircle.
     *
     * @param $area
     *
     * @return mixed
     */
    public function getCircle($area)
    {
        return $area['coords']['cx'].','.$area['coords']['cy'].','.$area['coords']['radius'];
    }

    /**
     * Get Array of block seat.
     *
     * @param $stadiumBlockSeat
     *
     * @return mixed
     */
    public function getSeatArray($stadiumBlockSeat, $requestData, $bookedTickets)
    {
        $stadiumBlockSeatData = [];
        foreach ($stadiumBlockSeat as $key => $stadiumBlockSeatVal) {
            $stadiumBlockSeatData[$stadiumBlockSeatVal->row][$key]['class'] = 'is-available';
            if(json_decode($requestData['selectedSeats'])) {
                foreach (json_decode($requestData['selectedSeats']) as $selectedSeatsVal) {
                    if ($stadiumBlockSeatVal->row == $selectedSeatsVal->row && $stadiumBlockSeatVal->seat == $selectedSeatsVal->seat && $requestData['blockId'] == $selectedSeatsVal->stadium_block_id) {
                        $stadiumBlockSeatData[$stadiumBlockSeatVal->row][$key]['class'] = 'is-picked';
                    }
                }
            }

            $stadiumBlockSeatData[$stadiumBlockSeatVal->row][$key]['id'] = $stadiumBlockSeatVal->id;
            $stadiumBlockSeatData[$stadiumBlockSeatVal->row][$key]['type'] = $stadiumBlockSeatVal->type;
            $stadiumBlockSeatData[$stadiumBlockSeatVal->row][$key]['seat'] = $stadiumBlockSeatVal->seat;
            foreach ($bookedTickets as $bookedTicketKey => $bookedTicketValue)
            {
                if($stadiumBlockSeatVal->id == $bookedTicketValue)
                {
                    $stadiumBlockSeatData[$stadiumBlockSeatVal->row][$key]['class'] = 'is-booked';
                }
            }
        }

        return $stadiumBlockSeatData;
    }

    /**
     * Get pricing band seat array.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getPriceBandSeatArray($priceBandSeat, $getPriceRange = null)
    {
        $priceBandSeatData = [];
        foreach ($priceBandSeat as $key => $priceBandSeatval) {
            $priceBandSeatData[$priceBandSeatval->row][$key]['seatFrom'] = $priceBandSeatval->seat_from;
            $priceBandSeatData[$priceBandSeatval->row][$key]['seatTo'] = $priceBandSeatval->seat_to;
            $priceBandSeatData[$priceBandSeatval->row][$key]['price'] = number_format(round($priceBandSeatval->price + ($priceBandSeatval->price * $priceBandSeatval->vat_rate) / 100, 2), 2);
            $priceBandSeatData[$priceBandSeatval->row][$key]['displayName'] = $priceBandSeatval->display_name;
            $priceBandSeatData[$priceBandSeatval->row][$key]['blockId'] = $priceBandSeatval->stadium_block_id;
            $priceBandSeatData[$priceBandSeatval->row][$key]['pricingBandId'] = $priceBandSeatval->pricing_band_id;
            if($getPriceRange != null){
                foreach ($getPriceRange as $priceRangekey => $priceRangeval) {
                    if($priceBandSeatval->stadium_block_id == $priceRangeval->stadium_block_id  && $priceBandSeatval->display_name == $priceRangeval->display_name)
                    {
                        $priceBandSeatData[$priceBandSeatval->row][$key]['pricingRange'] = $priceRangeval->priceRange;
                    }
                }
            }

        }

        return $priceBandSeatData;
    }

    /**
     * Get Mearge Array of block seat and price.
     *
     * @param $seatData
     *
     * @return mixed
     */
    public function getPriceBlockSeatArray($priceBandSeatArray, $seatArray, $row)
    {
        $seatData = [];
        foreach ($row as $rowVal) {
            foreach ($seatArray[$rowVal->row] as $key => $seatArrayVal) {
                $seatData[$rowVal->row][$key]['seat'] = $seatArrayVal['seat'];
                $seatData[$rowVal->row][$key]['id'] = $seatArrayVal['id'];
                if ($seatArrayVal['type'] == 'Stairwell') {
                    $seatData[$rowVal->row][$key]['class'] = 'is-stairs';
                    $seatData[$rowVal->row][$key]['type'] = 'Stairwell';
                } elseif ($seatArrayVal['type'] == 'Seat') {
                    $seatData[$rowVal->row][$key]['class'] = $seatArrayVal['class'];
                    $seatData[$rowVal->row][$key]['type'] = 'Seat';
                } elseif ($seatArrayVal['type'] == 'Disabled') {
                    $seatData[$rowVal->row][$key]['class'] = 'is-disabled';
                    $seatData[$rowVal->row][$key]['type'] = 'Disabled';
                } else {
                    $seatData[$rowVal->row][$key]['type'] = '';
                    $seatData[$rowVal->row][$key]['class'] = '';
                }
                if (isset($priceBandSeatArray[$rowVal->row])) {
                    foreach ($priceBandSeatArray[$rowVal->row] as $priceBandSeatArraykey => $priceBandSeatArrayVal) {
                        if ($priceBandSeatArrayVal['seatFrom'] <= $seatArrayVal['seat'] && $priceBandSeatArrayVal['seatTo'] >= $seatArrayVal['seat']) {
                            $seatData[$rowVal->row][$key]['pricing_band'][$priceBandSeatArraykey]['price'] = $priceBandSeatArrayVal['price'];
                            $seatData[$rowVal->row][$key]['pricing_band'][$priceBandSeatArraykey]['pricingBandId'] = $priceBandSeatArrayVal['pricingBandId'];
                            $seatData[$rowVal->row][$key]['pricing_band'][$priceBandSeatArraykey]['displayName'] = $priceBandSeatArrayVal['displayName'];
                        }
                    }
                }
            }
        }

        return $seatData;
    }

    /**
     * Get stedium block.
     *
     * @param
     *
     * @return mixed
     */

    public function getStadiumBlock($clubId)
    {
        return StadiumBlock::where('club_id', $clubId)->get();
    }

    /**
     * Get stedium ganral setting.
     *
     * @param
     *
     * @return mixed
     */

    public function getStadiumGeneralSetting($clubId)
    {
        return StadiumGeneralSetting::where('club_id', $clubId)->first();
    }

    /**
     * Get price band seat array.
     *
     * @param
     *
     * @return mixed
     */

    public function getPriceBandSeat($clubId, $matchPricingBands)
    {
        return PricingBand::select('stadium_block_id', 'price', 'vat_rate', 'display_name', 'pricing_bands.id as pricing_band_id')
        					->join('pricing_band_seats', 'pricing_bands.id', '=', 'pricing_band_seats.pricing_band_id')
        					->where('club_id', $clubId)
        					->whereIn('pricing_bands.id', $matchPricingBands)
        					->where('is_active', 1)
        					->distinct()->get();
    }

    /**
     * Get user detail.
     *
     * @param
     *
     * @return mixed
     */

    public function userDetail($consumerId)
    {
        return Consumer::find($consumerId);
    }

    /**
     * Get match detail.
     *
     * @param
     *
     * @return mixed
     */
    public function matchDetail($matchId)
    {
        return Match::find($matchId);
    }

    /**
     * Get price range stedium block wise.
     *
     * @param
     *
     * @return mixed
     */

    public function getPriceRange($clubId, $matchPricingBands)
    {
        return PricingBand::join('pricing_band_seats', 'pricing_bands.id', '=', 'pricing_band_seats.pricing_band_id')
       					->selectRaw('stadium_block_id, display_name,
       					(CASE
       						WHEN MIN(ROUND(price + (price * vat_rate / 100), 2)) != MAX(ROUND(price + (price * vat_rate / 100), 2)) THEN (CONCAT("£", MIN(ROUND(price + (price * vat_rate / 100), 2))," - £", MAX(ROUND(price + (price * vat_rate / 100), 2))))
       					ELSE
       						CONCAT("£", MIN(ROUND(price + (price * vat_rate / 100), 2)))
       					END) as priceRange'
       					)
       					->where('club_id', $clubId)
       					->whereIn('pricing_bands.id', $matchPricingBands)
       					->where('is_active', 1)
       					->groupBy('pricing_bands.display_name','stadium_block_id')->get();
    }

    /**
     * Get available seat stedium block wise.
     *
     * @param totalSeat
     *
     * @return mixed
     */

    public function availableSeat($matchId, $clubId)
    {
        $stadiumBlocksSeatIds = $this->bookedTicket($matchId);
        $bookedTicketCount = StadiumBlockSeat::where('stadium_block_seats.type', 'Seat')->whereIn('id', $stadiumBlocksSeatIds)->selectRaw('stadium_block_id, COUNT(id) as bookedTicketCount')->groupBy('stadium_block_id')->pluck('bookedTicketCount', 'stadium_block_id')->toArray();
        $totalSeat = StadiumBlockSeat::whereHas('stadiumBlock', function($query) use ($clubId) {
        						$query->where('club_id', $clubId)
        							->where('is_active', 1);
        					})
        					->where('stadium_block_seats.type', 'Seat')
        					->selectRaw('stadium_block_id, COUNT(stadium_block_seats.id) as totalSeat')->groupBy('stadium_block_id')->pluck('totalSeat', 'stadium_block_id')->toArray();
        $ticketUnavailableSeat = TicketingUnavailableSeat::
        					whereHas('matchTicketing', function($query) use($matchId){
        						$query->where('match_id', $matchId);
        					})
        					->selectRaw('ticketing_unavailable_seats.`stadium_block_id`, (SELECT COUNT(id) FROM stadium_block_seats WHERE stadium_block_seats.`type`="Seat" AND stadium_block_seats.`row` = ticketing_unavailable_seats.`row` AND stadium_block_seats.`seat`>=ticketing_unavailable_seats.`seat_from` AND stadium_block_seats.`seat`<=ticketing_unavailable_seats.`seat_to` AND stadium_block_seats.`stadium_block_id`=ticketing_unavailable_seats.`stadium_block_id`) AS counts')
        					->get()->toArray();

        foreach ($ticketUnavailableSeat as $ticketUnavailableSeatKey => $ticketUnavailableSeatValue) {
        	if(isset($totalSeat[$ticketUnavailableSeatValue['stadium_block_id']]) && $ticketUnavailableSeatValue['counts'] > 0) {
        		$totalSeat[$ticketUnavailableSeatValue['stadium_block_id']] = $totalSeat[$ticketUnavailableSeatValue['stadium_block_id']] - $ticketUnavailableSeatValue['counts'];
        	}
        }
        foreach ($bookedTicketCount as $key => $value) {
            $totalSeat[$key] = $totalSeat[$key] - $value;
        }

        return $totalSeat;
    }
    public function bookedTicket($matchId)
    {
        $transactionIds = TicketTransaction::join('booked_tickets', 'booked_tickets.ticket_transaction_id','=','ticket_transactions.id')->where('match_id', $matchId);
        $sellMatchTicket = SellMatchTicket::whereIn('booked_ticket_id', $transactionIds->pluck('booked_tickets.id'))->where('is_active', 1)->where('is_sold', 0)->pluck('booked_ticket_id')->toArray();
        if($sellMatchTicket) {
            $transactionIds = $transactionIds->whereNotIn('booked_tickets.id', $sellMatchTicket);
        }
        $stadiumBlocksSeatIds = $transactionIds->pluck('stadium_block_seat_id');
        return $stadiumBlocksSeatIds;
    }

    /**
     * Handle logic to get stadium block seats.
     *
     * @param $seat
     * @param $blockId
     *
     * @return mixed
     */
    public function getStadiumBlockSeats($seat, $blockId)
    {
        return $this->stadiumBlockRepository->getStadiumBlockSeats($seat, $blockId);
    }

    /**
     * Handle logic to get stadium block.
     *
     * @param $id
     *
     * @return mixed
     */
    public function stadiumBlock($id)
    {
        return $this->stadiumBlockRepository->stadiumBlock($id);
    }

    /**
     * Handle logic to get blocks list.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getBlocks($clubId)
    {
        return $this->stadiumBlockRepository->getBlocks($clubId);
    }
     public function checkFileHeader($excel)
    {
        $fileData = Excel::toArray('', $excel, null, \Maatwebsite\Excel\Excel::XLSX)[0];
        foreach ($fileData as $key => $value) {
            foreach ($value as $datakey => $data) {
                if($key>0)
                {
                    if($datakey>0)
                    {

                        if (!in_array($value[$datakey], config('fanslive.STADIUM_BLOCK_TYPE') ) && $value[$datakey]!=null)
                        {
                            return false;
                        }       
                    }
                }
            }
        }
        return true;
    }
}
