<?php

namespace App\Services;

use App\Models\ClubBankDetail;
use App\Repositories\ClubRepository;
use App\Repositories\ClubBankDetailsRepository;
use App\Services\FootballAPI\Client\HttpClient;
use File;
use Spatie\Glide\GlideImageFacade as GlideImage;
use Storage;

/**
 * Club class to handle operator interactions.
 */
class ClubService
{
	/**
	 * @var predefined logo path
	 */
	protected $logoPath;

	/**
	 * The category repository instance.
	 *
	 * @var repository
	 */
	protected $repository;
	protected $ClubBankDetailsRepository;

	/**
	 * The football API client.
	 *
	 * @var client
	 */
	protected $client;

	/**
	 * Create a new service instance.
	 *
	 * @param ClubRepository $repository
	 */
	public function __construct(ClubRepository $repository, ClubBankDetailsRepository $ClubBankDetailsRepository)
	{
		$this->logoPath = config('fanslive.IMAGEPATH.club_logo');
		$this->repository = $repository;
		$this->clubBankDetailsRepository = $ClubBankDetailsRepository;

		$this->client = new Httpclient();
	}

	/**
	 * Destroy a match instance.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		unset($this->logoPath);
		unset($this->repository);
		unset($this->client);
	}

	/**
	 * Handle logic to create a club.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($user, $data)
	{
		if (isset($data['logo'])) {
			$logo = uploadImageToS3($data['logo'], $this->logoPath);
		} else {
			$logo['url'] = null;
			$logo['file_name'] = null;
		}
		$data['logo'] = $logo['url'];
		$data['logo_file_name'] = $logo['file_name'];

		$club = $this->repository->create($user, $data);

		if (isset($data['currency'])) {
			$bankData['club_id'] = $club->id;
			$bankData['bank_name'] = $data['bank_name'];
			$bankData['account_name'] = $data['account_name'];
			$bankData['account_number'] = $data['account_number'];
			$bankData['sort_code'] = $data['sort_code'];
			$bankData['bic'] = $club->currency === 'EUR' ? $data['bic'] : NULL;
			$bankData['iban'] = $club->currency === 'EUR' ? $data['iban'] : NULL;
			$this->clubBankDetailsRepository->create($bankData);
		}

		return $club;
	}

	/**
	 * Handle logic to update a given club.
	 *
	 * @param $user
	 * @param $club
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $club, $data)
	{
		if (isset($data['logo'])) {
			$existingLogo = $this->logoPath . $club->logo_file_name;
			$disk = Storage::disk('s3');
			$disk->delete($existingLogo);

			$logo = uploadImageToS3($data['logo'], $this->logoPath);
		} else {
			$logo['url'] = $club->logo;
			$logo['file_name'] = $club->logo_file_name;
		}
		$data['logo'] = $logo['url'];
		$data['logo_file_name'] = $logo['file_name'];

		$clubToUpdate = $this->repository->update($user, $club, $data);

		if (!empty($clubToUpdate)) {
			$bankDetails = ClubBankDetail::where('club_id', $clubToUpdate->id)->first();
			$this->clubBankDetailsRepository->update($bankDetails, $data, $clubToUpdate->currency);
		}

		return $clubToUpdate;
	}

	/**
	 * Handle logic to delete a given logo file.
	 *
	 * @param $club
	 *
	 * @return mixed
	 */
	public function deleteLogo($club)
	{
		$disk = Storage::disk('s3');
		$logo = $this->logoPath . $club->logo_file_name;

		return $disk->delete($logo);
	}

	/**
	 * Get club data.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($data)
	{
		$clubs = $this->repository->getData($data);

		return $clubs;
	}

	/**
	 * Get club detail.
	 *
	 * @param $clubId
	 *
	 * @return mixed
	 */
	public function getClubDetail($clubId)
	{
		$club = $this->repository->getClubDetail($clubId);

		return $club;
	}

	/**
	 * Set default club for consumer.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function setDefaultClub($data)
	{
		return $this->repository->setDefaultClub($data);
	}

	/**
	 * Create club if not exists.
	 *
	 * @return mixed
	 */
	public function createClub($externalAPPId)
	{
		$club = $this->client->get('/v2/teams/' . $externalAPPId);
		$club = json_decode($club, true);
		$clubData = [];
		$isImageUploaded = false;
		$clubData['name'] = $club['name'];
		$clubData['logo'] = null;
		$clubData['logo_file_name'] = null;

		if ($club['crestUrl'] != '' && @fopen($club['crestUrl'], 'r')) {
			$path = $club['crestUrl'];
			$filename = basename($path);
			$content = file($path);
			Storage::put($filename, $content);
			$svg = Storage::url($filename);
			$ext = pathinfo($svg, PATHINFO_EXTENSION);
			$file = basename($svg, '.' . $ext);

			try {
				$png = GlideImage::create('storage/app/' . $filename)->modify(['fm' => 'png'])->save('storage/app/' . $file . '.png');
				$logo = uploadImageFromUrlToS3($png, $this->logoPath);
				$isImageUploaded = true;
			} catch (\Exception $e) {
			}
			Storage::delete([$filename, $file . '.png']);
			if ($isImageUploaded === true) {
				$clubData['logo'] = $logo['url'];
				$clubData['logo_file_name'] = $logo['file_name'];
			}
		}

		$clubData['status'] = 'Hidden';
		$clubData['external_app_id'] = $externalAPPId;

		return $this->repository->createClub($clubData);
	}

	/**
	 * Get club bank detail.
	 *
	 * @param $clubId
	 *
	 * @return mixed
	 */
	public function getClubBankDetail($clubId)
	{
		$clubBankDetail = $this->repository->getClubBankDetail($clubId);

		return $clubBankDetail;
	}

	/**
	 * Get Club Count.
	 *
	 *
	 * @return mixed
	 */
	public function getClubCount()
	{
		$clubCount = $this->repository->getClubCount();

		return $clubCount;
	}
	/**
	 * Get Club Admin.
	 *
	 *
	 * @return mixed
	 */
	public function clubAdmin($clubId)
	{
		$clubAdmin = $this->repository->clubAdmin($clubId);
		return $clubAdmin;
	}
}
