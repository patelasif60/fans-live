<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Club;
use JavaScript;

class PaymentController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Select card and make payment.
     */
    public function selectCardAndMakePayment(Request $request, $clubId, $type)
    {
    	$club = Club::find($clubId);
        JavaScript::put([
                'merchantId' => config('payments.paymentiq.merchant_id'),
                'environment' => config('payments.paymentiq.environment'),
                'club_primary_colour' => $club->primary_colour,
                'club_secondary_colour' => $club->secondary_colour,
                'type' => $type,
                'url' => config('app.url')
        ]);
        return view('frontend.payment');
    }
}
