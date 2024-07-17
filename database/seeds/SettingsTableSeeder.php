<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Setting::truncate();

		Setting::create(['key' => 'minimum_card_fee_amount']);
		Setting::create(['key' => 'card_fee_percentage']);
		Setting::create(['key' => 'footer_text_for_receipt']);
		Setting::create(['key' => 'bank_fee']);
		Setting::create(['key' => 'max_transaction_amount']);
		Setting::create(['key' => 'threshold_transaction_minutes']);
	}
}
