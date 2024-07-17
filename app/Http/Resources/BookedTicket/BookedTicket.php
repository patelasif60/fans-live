<?php

namespace App\Http\Resources\BookedTicket;

use App\Http\Resources\MatchTicketingSponsor\MatchTicketingSponsor as MatchTicketingSponsorResource;
use App\Http\Resources\StadiumEntrance\StadiumEntrance as StadiumEntranceResource;
use App\Http\Resources\Match\MatchBrief as MatchBriefResource;
use App\Repositories\StadiumEntranceRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Image;
use QrCode;
use Storage;

class BookedTicket extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var mixed
     */
    public $resource;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $checkWalletDetails;

    /**
     * A stadium entrance repository.
     *
     * @var stadiumEntranceRepository
     */
    protected $stadiumEntranceRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Create a QRcode path  variable.
     *
     * @return void
     */
    protected $bookedTicketQrcodePath;

    public function __construct($resource, $checkWalletDetails = false)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->checkWalletDetails = $checkWalletDetails;
        $this->stadiumEntranceRepository = new StadiumEntranceRepository();
        $this->bookedTicketQrcodePath = config('fanslive.IMAGEPATH.booked_ticket_qrcode');
    }

    /**
     * Set wallet details flag.
     *
     * @param  $checkWalletDetails
     *
     * @return int
     */
    public function checkWalletDetailsFlag($checkWalletDetails = false)
    {
        $this->checkWalletDetails = $checkWalletDetails;

        return $this;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->stadiumEntranceRepository);
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
    	$disk = Storage::disk('s3');
    	$stadiumEntrances = $this->stadium_block_seat_id ? $this->stadiumBlockSeat->stadiumBlock->stadiumEntrances : $this->ticketTransaction->club->stadiumEntrances;
        $stadiumEntranceIds = $stadiumEntrances ? $stadiumEntrances->pluck('id')->toArray() : [];
        $stadiumEntranceNames = $this->stadiumEntranceRepository->getstadiumEntrances($stadiumEntranceIds);

        $result = [
            'id'                    => $this->id,
            'ticket_transaction_id' => $this->ticket_transaction_id,
            'match_id'              => $this->ticketTransaction->match_id,
            'receipt_number'        => $this->ticketTransaction->receipt_number,
            'stadium_block_seat_id' => $this->stadium_block_seat_id,
            'stadium_block_name'    => $this->stadium_block_seat_id ? $this->stadiumBlockSeat->stadiumBlock->name : null,
            'stadium_entrance_name' => $stadiumEntranceNames,
            'stadium_entrances'     => $stadiumEntrances,
            'row'                   => $this->stadium_block_seat_id ? $this->stadiumBlockSeat->row : null,
            'seat'                  => $this->stadium_block_seat_id ? $this->stadiumBlockSeat->seat : $this->seat,
            'type'                  => $this->stadium_block_seat_id ? $this->stadiumBlockSeat->type : null,
            'pricing_band_id'       => $this->pricing_band_id,
            'pricing_band_name'     => $this->pricingBand->display_name,
            'price'                 => formatNumber($this->price),
            // 'qrcode'                => (string) Image::make(
            //                                 QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_ticket', 'ticket_id' => $this->id, 'type' => 'Match']))
            //                             )->encode('data-url'),
            'transaction_type'      => 'match',
            'qrcode_url'            => $disk->url($this->bookedTicketQrcodePath . $this->id . '.png'),
        ];

        if($this->checkWalletDetails) {
            $result = array_merge($result, [
                'match'  => new MatchBriefResource($this->ticketTransaction->match)
            ]);
        }
        $resalePrice = null;
        if(isset($this->ticketTransaction->match->ticketing))
        {
            if($this->ticketTransaction->match->is_ticket_sale_enabled && $this->ticketTransaction->match->ticketing->allow_ticket_returns_resales){
                $resalePrice = $this->price - $this->ticketTransaction->match->ticketing->ticket_resale_fee_amount;
                if($this->ticketTransaction->match->ticketing->ticket_resale_fee_type == 'percentage')
                {
                    $resalePrice = $this->price - (($this->price*$this->ticketTransaction->match->ticketing->ticket_resale_fee_amount)/100);
                }
            }
        }

        $result = array_merge($result, [
                'resale_price'  => $resalePrice ? formatNumber($resalePrice) : $resalePrice
            ]);

        return $result;
    }
}
