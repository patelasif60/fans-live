<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Staff\UpdateRequest;
use App\Http\Resources\Staff\Staff as StaffResource;
use App\Models\Staff;
use App\Rules\ValidateEmail;
use App\Rules\ValidatePassword;
use App\Services\StaffService;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

/**
 * @group Staff
 *
 * APIs for Staff user.
 */
class StaffController extends BaseController
{
    /**
     * A staff service variable.
     *
     * @var service
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StaffService $service)
    {
        $this->service = $service;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->service);
    }

    /**
     * Update profile
     * Update staff profile details.
     *
     * @bodyParam email string required The email of the user. Example: abc@example.com
     * @bodyParam first_name string required The first name of the user. Example: Bill
     * @bodyParam last_name string required The last name of the user. Example: Gates
     */
    public function updateProfile(UpdateRequest $request)
    {
        $user = JWTAuth::user();
        $staff = $this->service->updateStaff(
            $user,
            $request->all()
        );

        if ($staff) {
            return response()->json([
                'message' => 'Staff updated successfully.',
            ]);
        }
    }

    /**
     * Get profile
     * Get staff profile details.
     *
     * @bodyParam id int required An id of the user. Example: 1
     */
    public function getProfile()
    {
    	$user = JWTAuth::user();
        $profile = Staff::where('user_id', $user->id)->first();

        return new StaffResource($profile);
    }
}
