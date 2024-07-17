<?php

namespace App\Repositories;

use App\Models\Staff;
use App\Models\User;
use DB;

/**
 * Repository class for Staff model.
 */
class StaffRepository
{
    /**
     * Handle logic to create a new staff user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $staffUser = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'is_verified' => 1,
            'password'   => bcrypt($data['password']),
            'type'       => 'Staff',
            'status'     => $data['status'],
        ]);

        $staff = Staff::create([
            'user_id' => $staffUser->id,
            'club_id' => $data['club'],
        ]);

        // All current roles will be removed from the user and replaced by the array given
        $staffUser->syncRoles(['staff']);

        return $staffUser;
    }

    /**
     * Handle logic to update a new staff user.
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
        if (!empty(trim($data['password']))) {
            $userDataArray['password'] = bcrypt(trim($data['password']));
        }

        $user->fill($userDataArray);
        $user->save();

        $staff = Staff::where('user_id', $user->id)->update([
            'club_id' => $data['club'],
        ]);

        return $user;
    }

    /**
     * Get staff user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        $staffUsers = DB::table('staff')
            ->join('users', 'users.id', '=', 'staff.user_id')
            ->join('clubs', 'clubs.id', '=', 'staff.club_id')
            ->leftjoin('club_categories', 'club_categories.id', '=', 'clubs.club_category_id')
            ->select('staff.*', 'users.*', 'clubs.name as club_name', 'club_categories.name as club_category_name');

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'staff.id';
            $sorttype = 'desc';
        }
        $staffUsers = $staffUsers->orderBy($sortby, $sorttype);

        if (isset($data['first_name']) && trim($data['first_name']) != '') {
            $staffUsers->where('users.first_name', 'like', '%'.$data['first_name'].'%');
        }

        if (isset($data['last_name']) && trim($data['last_name']) != '') {
            $staffUsers->where('users.last_name', 'like', '%'.$data['last_name'].'%');
        }

        if (isset($data['club_id']) && trim($data['club_id']) != '') {
            $staffUsers->where('staff.club_id', $data['club_id']);
        }

        $staffUsersList = [];

        if (!array_key_exists('pagination', $data)) {
            $staffUsers = $staffUsers->paginate($data['pagination_length']);
            $staffUsersList = $staffUsers;
        } else {
            $staffUsersList['total'] = $staffUsers->count();
            $staffUsersList['data'] = $staffUsers->get();
        }

        $response = $staffUsersList;

        return $response;
    }

    /**
     * Get staff user detail.
     *
     * @param $user
     *
     * @return mixed
     */
    public function getStaffDetail($userId)
    {
        $staff = Staff::where('user_id', $userId)->first();

        return $staff;
    }

    /**
     * Handle logic to update a staff user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function updateStaff($user, $data)
    {
        $userDataArray = [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
        ];

        $user->fill($userDataArray);
        $user->save();

        return $user;
    }
}
