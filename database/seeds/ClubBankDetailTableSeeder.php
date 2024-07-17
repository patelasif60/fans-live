<?php

use Illuminate\Database\Seeder;
use App\Models\ClubBankDetail;

class ClubBankDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('club_bank_details')->delete();
				ClubBankDetail::insert([
					[
						'club_id' => 1,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 2,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 3,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 4,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 5,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 6,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 7,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 8,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 9,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 10,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 11,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 12,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],
					[
						'club_id' => 13,
						'bank_name' => 'City National Bank',
						'account_name' => 'Mr x',
						'account_number' => 0000000000,
						'sort_code' => 000,
						'bic' => 'xxx',
						'iban' => 'xxx',
					],

				]);
    }
}
