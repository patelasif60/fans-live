<?php

namespace App\Http\Resources\BookedHospitalitySuite;

use Image;
use QrCode;
use Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Match\MatchBrief as MatchBriefResource;

class BookedHospitalitySuite extends JsonResource
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
    protected $bookedHospitalitySuiteTicketQrcodePath;

    public function __construct($resource, $checkWalletDetails = false)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->checkWalletDetails = $checkWalletDetails;
        $this->bookedHospitalitySuiteTicketQrcodePath = config('fanslive.IMAGEPATH.booked_hospitality_suite_qrcode');
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
            'hospitality_suite_transaction_id' => $this->hospitality_suite_transaction_id,
            'receipt_number' => $this->hospitalitySuiteTransaction->receipt_number,
            'seat' => $this->seat,
            // 'qrcode' => (string) Image::make(
            //                                 QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_ticket', 'ticket_id' => $this->id, 'type' => 'Hospitality']))
            //                             )->encode('data-url'),
            'transaction_type'      => 'hospitality',
            'qrcode_url'            => $disk->url($this->bookedHospitalitySuiteTicketQrcodePath . $this->id . '.png'),
        ];

        if($this->checkWalletDetails) {
            $result = array_merge($result, [
                'match' => new MatchBriefResource($this->hospitalitySuiteTransaction->match),
            ]);
        }

        return $result;
    }
}
