<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\User\Role\StoreRequest;
use App\Http\Requests\User\Role\UpdateRequest;
use App\Models\Role;
use App\Services\UserRoleService;
use Illuminate\Http\Request;
use JavaScript;

class UserRoleController extends Controller
{
    /**
     * A User Role service.
     *
     * @var CompetitionService
     */
    protected $userRoleService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRoleService $userRoleService)
    {
        $this->middleware('auth');
        $this->userRoleService = $userRoleService;
    }

    /**
     * Display a listing of user role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = config('fanslive.CLUB_PERMISSIONS');
        JavaScript::put([
            'permissions' => $permissions,
        ]);

        return view('backend.users.role.index');
    }

    /**
     * Show the form for creating new user role user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission_own = config('fanslive.CLUB_PERMISSIONS');

        return view('backend.users.role.create', compact('permission_own'));
    }

    /**
     * Store a newly created competition.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $userRole = $this->userRoleService->create(
            auth()->user(),
            $request->all()
        );
        if ($userRole) {
            flash('User role created successfully')->success();
        } else {
            flash('User role could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.role.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Role $role)
    {
        $permission_own = config('fanslive.CLUB_PERMISSIONS');

        return view('backend.users.role.edit', compact('role', 'permission_own'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $roleUpdate = $this->userRoleService->update(
            $role,
            $request->all()
        );

        if ($roleUpdate) {
            flash('User role updated successfully')->success();
        } else {
            flash('User role could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.role.index');
    }

    /**
     * Remove the specified category.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if ($role->delete()) {
            flash('User role deleted successfully')->success();
        } else {
            flash('User role could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.role.index');
    }

    /**
     * Get Competition list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRoleData(Request $request)
    {
        $roleList = $this->userRoleService->getData(
            $request->all()
        );

        return $roleList;
    }
}
