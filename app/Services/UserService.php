<?php

namespace App\Services;

use App\Mail\CreatePassword;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use Mail;
use DB;

/**
 * User class to handle operator interactions.
 */
class UserService
{
    /**
     * The user repository instance.
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create a new service instance.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle logic to create a cms user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($user, $data)
    {
        // Save user details.
        $user = $this->userRepository->create($user, $data);

        //Send email
        $user = $this->createVerifyUserToken($user);

        Mail::to($user)->send(new CreatePassword($user));

        return $user;
    }

    /**
     * Handle logic to create a cms user token.
     *
     * @param $user
     *
     * @return mixed
     */
    public function createVerifyUserToken($user)
    {
        if ($user->verifyUser) {
            $user->verifyUser->delete();
        }
        $user->verifyUser()->create([
            'token' => Str::random(40),
        ]);

        return $user->fresh('verifyUser');
    }

    /**
     * Handle logic to update a given cms user.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function update($user, $data)
    {
        $cmsUser = $this->userRepository->update($user, $data);

        return $cmsUser;
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
        $cmsUsers = $this->userRepository->getData($data);

        return $cmsUsers;
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
        $cmsUsers = $this->userRepository->getUserDetail($userId);

        return $cmsUsers;
    }

    /**
     * Update device token.
     *
     * @param $user
     *
     * @return mixed
     */
    public function updateDeviceToken($userId, $deviceToken)
    {
        $user = $this->userRepository->updateDeviceToken($userId, $deviceToken);
        return $user;
    }

    /**
     * Update device token.
     *
     * @param $user
     *
     * @return mixed
     */
    public function getUsersForDashboard($clubId = NULL)
    {
        $responseData = [];
        $cmsUsers = DB::table('cms')->join('users', 'users.id', '=', 'cms.user_id')->when($clubId, function($q)use($clubId){
                            $q->where('club_id', '=', $clubId);
                        })->count();
        $consumerUsers = DB::table('consumers')->join('users', 'users.id', '=', 'consumers.user_id')->when($clubId, function($q)use($clubId){
                            $q->where('club_id', '=', $clubId);
                        })->count();
        $staffUsers = DB::table('staff')->join('users', 'users.id', '=', 'staff.user_id')->when($clubId, function($q)use($clubId){
                            $q->where('club_id', '=', $clubId);
                        })->count();
        $responseData['cms_users'] = $cmsUsers;
        $responseData['consumer_users'] = $consumerUsers;
        $responseData['staff_users'] = $staffUsers;
        return $responseData;
    }
    /**
     * Get super Admin.
     *
     *
     * @return mixed
     */
    public function superAdmin()
    {
        $superAdmin = $this->userRepository->superAdmin();
        return $superAdmin;
    }
    /**
     * Get Club Admin.
     *
     *
     * @return mixed
     */
    public function clubAdmin($clubId)
    {
        $clubAdmin = $this->userRepository->clubAdmin($clubId);
        return $clubAdmin;
    }
}

