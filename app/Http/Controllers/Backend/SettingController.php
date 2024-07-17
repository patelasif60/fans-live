<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\setting\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$settings = Setting::whereIn('key', [
			'minimum_card_fee_amount',
			'card_fee_percentage',
			'footer_text_for_receipt',
			'bank_fee',
			'max_transaction_amount',
			'threshold_transaction_minutes',
		])->get();

		$settings = $settings->pluck('value', 'key')->toArray();

		return view('backend.settings.transaction', compact('settings'));
	}

	/**
	 * Update the transaction settings.
	 *
	 * @param UpdateSettingsRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(UpdateSettingsRequest $request)
	{
		$rows = $request->only([
			'minimum_card_fee_amount',
			'bank_fee',
			'card_fee_percentage',
			'footer_text_for_receipt',
			'payment_kit_charge',
			'max_transaction_amount',
			'threshold_transaction_minutes',
		]);

		foreach ($rows as $key => $value) {
			Setting::updateOrCreate(
				['key' => $key],
				['value' => $value]
			);
		}
		flash('Settings updated successfully')->success();
		return redirect()->route('backend.setting.index');
	}

}
