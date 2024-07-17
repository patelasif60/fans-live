<?php

namespace App\Http\Resources\BookedEvent;

use Image;
use QrCode;
use Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Event\EventBrief as EventBriefResource;

class BookedEvent extends JsonResource
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
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Create a QRcode path  variable.
     *
     * @return void
     */
    protected $bookedEventTicketQrcodePath;

    public function __construct($resource, $checkWalletDetails = false)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->checkWalletDetails = $checkWalletDetails;
        $this->bookedEventTicketQrcodePath = config('fanslive.IMAGEPATH.booked_event_qrcode');
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
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    	$disk = Storage::disk('s3');
        $result = [
            'id' => $this->id,
            'event_transaction_id' => $this->event_transaction_id,
            'receipt_number' => $this->eventTransaction->receipt_number,
            'seat' => $this->seat,
            // 'qrcode' => (string) Image::make(
            //                                 QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_ticket', 'ticket_id' => $this->id, 'type' => 'Event']))
            //                             )->encode('data-url'),
            'transaction_type'      => 'event',
            'qrcode_url'            => $disk->url($this->bookedEventTicketQrcodePath . $this->id . '.png'),
        ];

        if($this->checkWalletDetails) {
            $result = array_merge($result, [
                'event' => new EventBriefResource($this->eventTransaction->event),
            ]);
        }

        return $result;
    }
}
