<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CMS\StoreRequest;
use App\Http\Requests\User\CMS\UpdateRequest;
use App\Mail\CreatePassword;
use App\Models\Club;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Auth;
use Hash;
use JavaScript;
use Illuminate\Http\Request;
use Mail;

class UserController extends Controller
{
	/**
	 * A User service.
	 *
	 * @var UserService
	 */
	protected $userService;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(UserService $userService)
	{
		$this->middleware('auth');
		$this->userService = $userService;
	}

	/**
	 * Change password.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function changepassword()
	{
		return view('backend.users.changepassword');
	}

	/**
	 * Store the form request for changing logged in user password.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function storechangepassword(Request $request)
	{
		$user = Auth::user();
		if (Hash::check($request->current_password, $user->password)) {
			$user->password = bcrypt($request->password);
			if ($user->save()) {
				flash('Password changed successfully!')->success();
			} else {
				flash('Something went wrong!')->error();
			}
		} else {
			flash('You have entered wrong current password!')->error();
		}

		return redirect()->route('backend.changepassword');
	}

	/**
	 * Display a listing of cms users.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCmsUsers($club = null)
	{
		$cmsUsers = User::all();
		$clubs = Club::all();
		JavaScript::put(['clubdata' => null]);
		if ($club) {
			$clubData = Club::where('slug', $club)->get()->first();
			JavaScript::put(['clubdata' => $clubData]);
			return view('backend.users.cms.index', compact('cmsUsers', 'clubs', 'clubData'));
		}
		return view('backend.users.cms.index', compact('cmsUsers', 'clubs'));
	}

	/**
	 * Show the form for creating a new cms users.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function createCmsUser($club = null)
	{
		$status = config('fanslive.USER_STATUS');
		$clubs = Club::all();
		$roles = Role::whereNotIn('name', config('fanslive.INITIAL_ROLES'))->get();
		JavaScript::put(['clubdata' => null]);
		if ($club) {
			$clubData = Club::where('slug', $club)->get()->first();
			JavaScript::put(['clubdata' => $clubData]);
			return view('backend.users.cms.create', compact('status', 'clubs', 'roles', 'clubData'));
		}

		return view('backend.users.cms.create', compact('status', 'clubs', 'roles'));
	}

	/**
	 * Store a newly created cms user.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Illuminate\Http\Request $user
	 * @param \Illuminate\Http\Request $club
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function storeCmsUser(StoreRequest $request, User $user, $club = null)
	{
		$user = $this->userService->create(
			$user,
			$request->all()
		);
		if ($user) {
			flash('CMS user created successfully')->success();
		} else {
			flash('CMS user could not be created. Please try again.')->error();
		}

		if ($club) {
			return redirect()->route('backend.cms.club.index', ['club' => $club]);
		}
		return redirect()->route('backend.cms.index');
	}

	/**
	 * Show the form for editing a CMS user.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function editCmsUser(Request $request, $userId, $club = null)
	{
		$temp = $userId;
		if ($club != null) {
			$userId = $club;
			$club = $temp;
		}
		$status = config('fanslive.USER_STATUS');
		$clubs = Club::all();
		$roles = Role::whereNotIn('name', config('fanslive.INITIAL_ROLES'))->get();
		$user = $this->userService->getUserDetail($userId);
		$userRoles = User::where('id', $userId)->first()->getRoleNames()->toArray();
		JavaScript::put(['clubdata' => null]);
		if ($club) {
			$clubData = Club::where('slug', $club)->get()->first();
			JavaScript::put(['clubdata' => $clubData]);
			return view('backend.users.cms.edit', compact('user', 'status', 'clubs', 'roles', 'userRoles', 'clubData'));
		}

		return view('backend.users.cms.edit', compact('user', 'status', 'clubs', 'roles', 'userRoles'));
	}

	/**
	 * Update the specified CMS user.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateCmsUser(UpdateRequest $request, $club, User $user = null)
	{
		$temp = $club;
		if ($user == null) {
			$club = $user;
			$user = $temp;
		}
		$user = $this->userService->update(
			$user,
			$request->all()
		);
		if ($user) {
			flash('CMS user updated successfully')->success();
		} else {
			flash('CMS user could not be updated. Please try again.')->error();
		}
		if ($club) {
			return redirect()->route('backend.cms.club.index', ['club' => $club]);
		}
		return redirect()->route('backend.cms.index');
	}

	/**
	 * Remove the specified cms user.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroyCmsUser($club, User $user = null)
	{
		if ($user == null) {
			$user = $club;
			$club = null;
		}
		if ($user->delete()) {
			flash('CMS user deleted successfully')->success();
		} else {
			flash('CMS user could not be deleted. Please try again.')->error();
		}

		if ($club) {
			return redirect()->route('backend.cms.club.index', ['club' => $club]);
		}
		return redirect()->route('backend.cms.index');
	}

	/**
	 * Get CMS user data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCMSUserData(Request $request)
	{
		$cmsUsers = $this->userService->getData(
			$request->all()
		);

		return $cmsUsers;
	}

	/**
	 * Check if an email is unique or not.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function checkEmail(Request $request)
	{
		$email = $request->email;

		return checkExistingEmail($email);
	}

	/**
	 * open model pop up of permission
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function viewrole(Request $request)
	{
		$role = role::find($request['roleID']);
		$permission = $role->permissions()->pluck('name')->toArray();
		$permission_own = config('fanslive.CLUB_PERMISSIONS');
		return view('backend.users.cms.viewpermission', compact('permission_own', 'permission'));
	}

	/**
	 * Send mail to user for new password link.
	 *
	 * @param User $user The user model that is being edited.
	 * @return \Illuminate\Http\Response
	 */
	public function sendEmail(User $user)
	{
		if ($user->is_verified == 0) {
			$user = $this->userService->createVerifyUserToken($user);
			Mail::to($user)->send(new CreatePassword($user));
		}

		flash('Email sent successfully')->success();

		return redirect()->route('backend.cms.index');
	}

	/**
	 * Send mail to user for new password link.
	 *
	 * @param $club $club The club model.
	 * @param $userId The user id for getting the user data.
	 * @return \Illuminate\Http\Response
	 */
	public function sendClubEmail($club, $userId )
	{
		$user = User::findorfail($userId);
		if ($user->is_verified == 0) {
			$user = $this->userService->createVerifyUserToken($user);
			Mail::to($user)->send(new CreatePassword($user));
		}
			flash('Email sent successfully')->success();

			return redirect()->route('backend.cms.club.index' , ['club' => $club]);
	}

}
