<?php

namespace App\Services;

use App\Repositories\CompetitionRepository;
use Storage;

/**
 * User class to handle operator interactions.
 */
class CompetitionService
{
    /**
     * @var predefined logo path
     */
    protected $logoPath;
    /**
     * The user repository instance.
     *
     * @var CompetitionRepository
     */
    private $competitionRepository;

    /**
     * Create a new service instance.
     *
     * @param CompetitionRepository $CompetitionRepository
     */
    public function __construct(CompetitionRepository $competitionRepository)
    {
        $this->logoPath = config('fanslive.IMAGEPATH.competition_logo');
        $this->competitionRepository = $competitionRepository;
    }

    /**
     * Handle logic to create a competition user.
     *
     * @param $user
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

        $competition = $this->competitionRepository->create($user, $data);

        return $competition;
    }

    /**
     * Handle logic to update a given competition.
     *
     * @param $data
     * @param $competition
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $competition, $data)
    {
        if (isset($data['logo'])) {
            $existingLogo = $this->logoPath.$competition->logo_file_name;
            $disk = Storage::disk('s3');
            $disk->delete($existingLogo);

            $logo = uploadImageToS3($data['logo'], $this->logoPath);
        } else {
            $logo['url'] = $competition->logo;
            $logo['file_name'] = $competition->logo_file_name;
        }
        $data['logo'] = $logo['url'];
        $data['logo_file_name'] = $logo['file_name'];

        $competitionToUpdate = $this->competitionRepository->update($user, $competition, $data);

        return $competitionToUpdate;
    }

    public function deleteLogo($competition)
    {
        $disk = Storage::disk('s3');
        $logo = $this->logoPath.$competition->logo_file_name;

        return $disk->delete($logo);
    }

    /**
     * Get Competition data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        $competition = $this->competitionRepository->getData($data);

        return $competition;
    }

	/**
	 * Get Competition Count.
	 *
	 *
	 * @return mixed
	 */
	public function getCompetitionCount()
	{
		$competitionCount = $this->competitionRepository->getCompetitionCount();

		return $competitionCount;
	}

}
