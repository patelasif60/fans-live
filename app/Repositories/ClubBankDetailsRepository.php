<?php


namespace App\Repositories;

use App\Models\ClubBankDetail;
use DB;

/**
 * Repository class for  model.
 */
class ClubBankDetailsRepository extends BaseRepository
{
	/**
	 * Handle logic to create a Club bank detail.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($data)
	{
		$clubBankDetail = ClubBankDetail::create([
			'club_id' => $data['club_id'],
			'bank_name' => $data['bank_name'],
			'account_name' => $data['account_name'],
			'account_number' => $data['account_number'],
			'sort_code' => $data['sort_code'],
			'bic' => $data['bic'],
			'iban' => $data['iban'],
		]);

		return $clubBankDetail;
	}

	/**
	 * Handle logic to update a category.
	 *
	 * @param $clubId
	 * @param $bankData
	 *
	 * @return mixed
	 */
	public function update($clubToUpdate, $data, $currency)
	{
		$clubToUpdate->fill([
			'bank_name' => $data['bank_name'],
			'account_name' => $data['account_name'],
			'account_number' => $data['account_number'],
			'sort_code' => $data['sort_code'],
			'bic' => $currency === 'EUR' ? $data['bic'] : NULL,
			'iban' => $currency === 'EUR' ? $data['iban'] : NULL,
		]);
		$clubToUpdate->save();

		return $clubToUpdate;
	}

}
