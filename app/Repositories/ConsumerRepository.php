<?php

namespace App\Repositories;

use App\Models\Consumer;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;

/**
 * Repository class for  model.
 */
class ConsumerRepository extends BaseRepository
{
    /**
     * Handle logic to create a new consumer user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $consumerUser = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'type'       => 'Consumer',
            'status'     => $data['status'],
			'remember_token' => Str::random(40),
        ]);

        $consumer = Consumer::create([
            'user_id'       => $consumerUser->id,
            'club_id'       => $data['club'],
            'time_zone'     => $data['time_zone'],
            'date_of_birth' => convertDateFormat($data['dob'], config('fanslive.DATE_CMS_FORMAT.php')),
        ]);

        // All current roles will be removed from the user and replaced by the array given
        $consumerUser->syncRoles(['consumer']);

        return $consumerUser;
    }

    /**
     * Handle logic to update a consumer user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $data)
    {
        $userDataArray = [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'status'     => $data['status'],
        ];

        $user->fill($userDataArray);
        $user->save();

        $consumer = Consumer::where('user_id', $user->id)->update([
            'club_id'       => $data['club'],
			'time_zone'     => $data['time_zone'],
            'date_of_birth' => convertDateFormat($data['dob'], config('fanslive.DATE_CMS_FORMAT.php')),
        ]);

        return $user;
    }

    /**
     * Get consumer user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        $consumerUsers = DB::table('consumers')
            ->join('users', 'users.id', '=', 'consumers.user_id')
            ->leftjoin('clubs', 'clubs.id', '=', 'consumers.club_id')
            ->leftjoin('club_categories', 'club_categories.id', '=', 'clubs.club_category_id')
            ->select('consumers.*', 'users.*', 'clubs.name as club_name', 'club_categories.name as club_category_name');

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'consumers.id';
            $sorttype = 'desc';
        }
        $consumerUsers = $consumerUsers->orderBy($sortby, $sorttype);

        if (isset($data['first_name']) && trim($data['first_name']) != '') {
            $consumerUsers->where('users.first_name', 'like', '%'.$data['first_name'].'%');
        }

        if (isset($data['last_name']) && trim($data['last_name']) != '') {
            $consumerUsers->where('users.last_name', 'like', '%'.$data['last_name'].'%');
        }

        if (isset($data['club_id']) && trim($data['club_id']) != '') {
            $consumerUsers->where('consumers.club_id', $data['club_id']);
        }

        $consumerUsersList = [];

        if (!array_key_exists('pagination', $data)) {
            $consumerUsers = $consumerUsers->paginate($data['pagination_length']);
            $consumerUsersList = $consumerUsers;
        } else {
            $consumerUsersList['total'] = $consumerUsers->get()->count();
            $consumerUsersList['data'] = $consumerUsers->get();
        }

        $response = $consumerUsersList;

        return $response;
    }

    /**
     * Get consumer user detail.
     *
     * @param $user
     *
     * @return mixed
     */
    public function getConsumerDetail($userId)
    {
        $consumer = Consumer::join('users', 'users.id', '=', 'consumers.user_id')
            ->where('users.id', $userId)
            ->first();

        return $consumer;
    }

    /**
     * Handle logic to update a consumer user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function updateConsumer($user, $data)
    {
        $userDataArray = [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
        ];

        $user->fill($userDataArray);
        $user->save();

        $consumer = Consumer::where('user_id', $user->id)->update([
            'date_of_birth'  => Carbon::parse($data['date_of_birth'])->format('Y-m-d'),
            'receive_offers' => $data['receive_offers'] == true ? 1 : 0,
            'time_zone'      => $data['timezone']
        ]);

        return $user;
    }

    /**
     * Handle logic to delete a consumer.
     *
     * @param $userId
     *
     * @return mixed
     */
    public function deleteConsumer($userId)
    {
        $user = User::where('id', $userId)->delete();

        return $user;
    }

    /**
     * Handle logic to update a consumer settings.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function updateSettings($user, $data)
    {
        $consumer = Consumer::where('user_id', $user->id)->first();
        $allSettings = $consumer->settings;
        foreach ($data as $key => $value) {
            $allSettings[$key] = $value;
        }
        $consumer->settings = $allSettings;
        $consumer->update();

        return $consumer;
    }

    /**
     * Get device token array
     *
     * @param $consumerIds
     * @param $membershipPackageIds
     *
     * @return mixed
     */
    public function getDeviceTokensArr($consumerIds = [], $membershipPackageIds = [])
    {
        $deviceTokens = DB::table('consumers')
                        ->leftjoin('consumer_membership_package', 'consumer_membership_package.consumer_id', '=', 'consumers.id')
                        ->leftjoin('users','users.id','=','consumers.user_id')
                        ->select('consumers.id as consumer_id','users.device_token',DB::raw('IF(consumer_membership_package.membership_package_id, consumer_membership_package.membership_package_id, ' . config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID') . ') as membership_package_id'))
                        ->whereIn('consumers.id', $consumerIds)
                        ->where(function($q){
                            $q->where('consumer_membership_package.is_active', TRUE);
                            $q->orWhereNull('consumer_membership_package.is_active');
                        })
                        ->whereNotNull('users.device_token')
                        ->havingRaw('membership_package_id IN ('.implode(',', $membershipPackageIds).')')
                        ->get();
        if(count($deviceTokens) > 0) {
            return [
                'consumers_with_device_token' => $deviceTokens->pluck('device_token','consumer_id')->toArray(),
                'device_tokens' => $deviceTokens->pluck('device_token')->toArray()
            ];
        }
    }

    /**
     * Get consumer user detail.
     *
     * @param $userId
     *
     * @return mixed
     */
    public function getConsumerDetailWithClub($userId)
    {
        $consumer = Consumer::with('club')
        	->whereHas('user', function($query) use($userId) {
        		return $query->where('users.id', $userId);
        	})
            ->first();

        return $consumer;
    }
}
