<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\SendDeviceTokenRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use JWTAuth;

/**
 * @group User
 *
 * APIs for User.
 */
class UserController extends Controller
{

   /**
    * Service initialize variable
    *
    */
   protected $service; 

   /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Send Device Token
     *
     * @bodyParam device_token text required
     *
     *
     * @return mixed
    */
    public function sendDeviceToken(SendDeviceTokenRequest $request)
    {
        $user = JWTAuth::user();
        $updateUser = $this->service->updateDeviceToken(
            $user->id,
            $request->device_token
        );

        if ($updateUser) {
            return response()->json([
                'message' => 'Device token added successfully.'
            ]);
        }
    }
}
