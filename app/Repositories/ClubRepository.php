<?php

namespace App\Repositories;

use App\Models\Club;
use App\Models\ClubBankDetail;
use App\Models\Consumer;
use App\Models\CMS;
use DB;
use Illuminate\Support\Str;
use JWTAuth;

class ClubRepository extends BaseRepository
{
	/**
	 * Create a new ContactRepository instance.
	 *
	 * @param \App\Models\Contact $contact
	 *
	 * @return void
	 */
	public function __construct(Club $club)
	{
		$this->model = $club;
	}

	/**
	 * Handle logic to create a new club.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($user, $data)
	{
		$club = Club::create([
			'name' => $data['name'],
			'slug' => $this->generateSlug($data['name']),
			'club_category_id' => $data['category'],
			'logo' => $data['logo'],
			'logo_file_name' => $data['logo_file_name'],
			'status' => $data['status'],
			'external_app_id' => $data['external_api_team_id'],
			'primary_colour' => $data['primary_colour'],
			'secondary_colour' => $data['secondary_colour'],
			'time_zone' => $data['time_zone'],
			'currency' => $data['currency'],
			'created_by' => $user->id,
			'updated_by' => $user->id,
		]);

		$this->attachCompetitions($club, json_decode($data['club_competitions']));

		return $club;
	}

	/**
	 * Generate slug.
	 */
	public function generateSlug($title)
	{
		$slug = Str::slug($title);
		$slugCount = count(Club::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get());

		return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
	}

	/**
	 * Handle logic to update a club.
	 *
	 * @param $user
	 * @param $club
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $club, $data)
	{
		$club->fill([
			'name' => $data['name'],
			'club_category_id' => $data['category'],
			'logo' => $data['logo'],
			'logo_file_name' => $data['logo_file_name'],
			'status' => $data['status'],
			'external_app_id' => $data['external_api_team_id'],
			'primary_colour' => $data['primary_colour'],
			'secondary_colour' => $data['secondary_colour'],
			'time_zone' => $data['time_zone'],
			'currency' => $data['currency'],
			'created_by' => $user->id,
			'updated_by' => $user->id,
		]);
		$club->save();

		$this->attachCompetitions($club, json_decode($data['club_competitions']));

		return $club;
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
		$clubs = DB::table('clubs')
			->join('club_categories', 'club_categories.id', '=', 'clubs.club_category_id')
			->select('clubs.*', 'club_categories.name as category_name');

		if (isset($data['sortby'])) {
			$sortby = $data['sortby'];
			$sorttype = $data['sorttype'];
		} else {
			$sortby = 'clubs.id';
			$sorttype = 'desc';
		}
		$clubs = $clubs->orderBy($sortby, $sorttype);

		if (isset($data['name']) && trim($data['name']) != '') {
			$clubs->where('clubs.name', 'like', '%' . $data['name'] . '%');
		}

		if (isset($data['category_id']) && trim($data['category_id']) != '') {
			$clubs->where('clubs.club_category_id', $data['category_id']);
		}

		$clubsList = [];

		if (!array_key_exists('pagination', $data)) {
			$clubs = $clubs->paginate($data['pagination_length']);
			$clubsList = $clubs;
		} else {
			$clubsList['total'] = $clubs->count();
			$clubsList['data'] = $clubs->get();
		}

		$response = $clubsList;

		return $response;
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
		$club = Club::where('id', $clubId)->first();

		return $club;
	}

	/**
	 * Get clubs collection.
	 *
	 * @param \App\Models\Contact $contact
	 *
	 * @return void
	 */
	public function getClubs()
	{
		return $this->model->get();
	}

	/**
	 * Attach competitions to club.
	 *
	 * @param $club
	 * @param $competitions
	 *
	 * @return mixed
	 */
	public function attachCompetitions($club, $competitions)
	{
		$club->competitions()->sync($competitions);
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
		$user = JWTAuth::user();
		if (Club::find($data['club_id'])) {
			$consumer = Consumer::where('user_id', $user->id)->update(['club_id' => $data['club_id']]);

			return true;
		}

		return false;
	}

	/**
	 * Get club.
	 *
	 * @param  $externalAppId
	 *
	 * @return void
	 */
	public function getClub($externalAppId)
	{
		$club = Club::where('external_app_id', $externalAppId)->first();

		return $club;
	}

	/**
	 * Get club.
	 *
	 * @param  $clubId
	 *
	 * @return void
	 */
	public function getClubBankDetail($clubId)
	{
		$clubBankDetail = ClubBankDetail::where('club_id', $clubId)->first();

		return $clubBankDetail;
	}

	/**
	 * Create club.
	 *
	 * @param  $data
	 *
	 * @return void
	 */
	public function createClub($data)
	{
		$club = Club::create([
			'name' => $data['name'],
			'slug' => $this->generateSlug($data['name']),
			'logo' => $data['logo'],
			'logo_file_name' => $data['logo_file_name'],
			'status' => $data['status'],
			'external_app_id' => $data['external_app_id'],
		]);

		return $club;
	}

	/**
	 * Handle logic to get Club Count.
	 *
	 *
	 * @return mixed
	 */
	public function getClubCount()
	{
		$clubCount = Club::where('status', 'Published')->count();
		return $clubCount;
	}
}
