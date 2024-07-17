<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Models\Role;

/**
 * Repository class for User model.
 */
class UserRoleRepository extends BaseRepository
{
    /**
     * Handle logic to create a new cms user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($role, $data)
    {
        $roleUser = Role::create([
            'name'         => $data['display_name'],
            'display_name' => $data['display_name'],
        ]);
        $roleUser->givePermissionTo($data['permission']);

        return $roleUser;
    }

    /**
     * Handle logic to update a user role.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($role, $data)
    {
        //print_r($data['permission']);die;
        $role->fill([
            'name'         => $data['display_name'],
            'display_name' => $data['display_name'],
        ]);
        $role->save();
        $role->syncPermissions($data['permission']);

        return $role;
    }

    /**
     * Get User role data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        $initialRoles = config('fanslive.INITIAL_ROLES');
        $userRole = Role::with('permissions')->whereNotIn('name', $initialRoles);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'id';
            $sorttype = 'desc';
        }
        $userRole = $userRole->orderBy($sortby, $sorttype);

        $userRoleArray = [];

        if (!array_key_exists('pagination', $data)) {
            $userRole = $userRole->paginate($data['pagination_length']);
            $userRoleArray = $userRole;
        } else {
            $userRoleArray['total'] = $userRole->count();
            $userRoleArray['data'] = $userRole->get();
        }

        $response = $userRoleArray;

        return $response;
    }
}
