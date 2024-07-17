<?php

namespace App\Repositories;

use App\Models\CMS;
use App\Models\User;
use DB;

/**
 * Repository class for User model.
 */
class UserRepository extends BaseRepository
{
    /**
     * Handle logic to create a new cms user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create(User $user, $data)
    {
        // Store user details
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'type'       => $data['role'] == 'superadmin' ? 'Superadmin' : 'Clubadmin',
            'status'     => $data['status'],
        ]);

        $cms = CMS::create([
            'user_id' => $user->id,
            'club_id' => $data['club'],
            'company' => $data['company'],
            'notes'   => $data['notes'],
        ]);

        // All current roles will be removed from the user and replaced by the array given
        $user->syncRoles($data['role'] == 'superadmin' ? $data['role'] : $data['club_admin_roles']);

        return $user;
    }

    /**
     * Handle logic to update a cms user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $data)
    {
        $user->fill([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'type'       => $data['role'] == 'superadmin' ? 'Superadmin' : 'Clubadmin',
            'status'     => $data['status'],
        ]);
        $user->save();

        $cms = CMS::where('user_id', $user->id)->update([
            'company' => $data['company'],
            'notes'   => $data['notes'],
            'club_id' => $data['role'] == 'superadmin' ? null : $data['club'],
        ]);

        $user->syncRoles($data['role'] == 'superadmin' ? $data['role'] : $data['club_admin_roles']);

        return $user;
    }

    /**
     * Get CMS user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        $cmsUsers = DB::table('cms')
            ->join('users', 'users.id', '=', 'cms.user_id')
            ->leftjoin('clubs', 'clubs.id', '=', 'cms.club_id')
            ->leftjoin('club_categories', 'club_categories.id', '=', 'clubs.club_category_id')
            ->leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('cms.*', 'users.*', 'clubs.name as club_name', 'club_categories.name as club_category_name', 'roles.name as role_name');


        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'cms.id';
            $sorttype = 'desc';
        }
        $cmsUsers = $cmsUsers->orderBy($sortby, $sorttype);

        if (isset($data['first_name']) && trim($data['first_name']) != '') {
            $cmsUsers->where('users.first_name', 'like', '%'. $data['first_name'] . '%');
        }
        if (isset($data['last_name']) && trim($data['last_name']) != '') {
            $cmsUsers->where('users.last_name', 'like', '%' . $data['last_name'] . '%');
        }
        if (isset($data['club_id']) && trim($data['club_id']) != '') {
            if($data['club_id']=='Superadmin')
            {
                $cmsUsers->where('users.type', $data['club_id']);
            }
            else
            {
                $cmsUsers->where('cms.club_id', $data['club_id']);
            }
        }

        $cmsUsersList = [];

        if (!array_key_exists('pagination', $data)) {
            $cmsUsers = $cmsUsers->paginate($data['pagination_length']);
            $cmsUsersList = $cmsUsers;
        } else {
            $cmsUsersList['total'] = $cmsUsers->count();
            $cmsUsersList['data'] = $cmsUsers->get();
        }

        $response = $cmsUsersList;

        return $response;
    }

    /**
     * Get CMS user detail.
     *
     * @param $user
     *
     * @return mixed
     */
    public function getUserDetail($userId)
    {
        $cmsUser = CMS::join('users', 'users.id', '=', 'cms.user_id')
            ->where('users.id', $userId)
            ->first();

        return $cmsUser;
    }

    /**
     * Handle logic to update device token.
     *
     * @param $userId
     * @param $deviceToken
     *
     * @return mixed
     */
    public function updateDeviceToken($userId, $deviceToken)
    {
        $user = User::where('id', $userId)->update([
            'device_token' => $deviceToken,
        ]);
        return $user;
    }

    /**
     * Handle logic to modify device token.
     *
     * @param $oldToken
     * @param $newToken
     *
     * @return mixed
     */
    public function modifyDeviceToken($oldToken, $newToken)
    {
        $user = User::where('device_token', $oldToken)->update([
            'device_token' => $newToken,
        ]);
        return $user;
    }

    /**
     * Handle logic to remove device token.
     *
     * @param $deviceTokens
     *
     * @return mixed
     */
    public function removeDeviceToken($deviceTokens)
    {
        $user = User::whereIn('device_token', $deviceTokens)->update([
            'device_token' => NULL,
        ]);
        return $user;
    }
    /**
     * .get club admin
     *
     *
     * @return mixed
     */
    public function superAdmin()
    {
        $superAdmin = User::where('is_verified',1)->where('status',"Active")->where('type','Superadmin')->pluck('email','id');
        return $superAdmin;
    }
    /**
     * .get club admin
     *
     *
     * @return mixed
     */
    public function clubAdmin($clubId)
    {
        $clubAdmin = CMS::join('users', 'users.id', '=', 'cms.user_id')->where('club_id',$clubId)->where('is_verified',1)->where('status',"Active")->pluck('email','user_id');
        return $clubAdmin;
    }
}
