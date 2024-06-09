<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log; 
use App\Traits\ApiResponseTrait;
use App\Models\User;
use App\Utilities\Enum\BasicStatusEnum;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Entities\UserAssignedRole;
use Modules\User\Services\UserService;
use Modules\User\Http\Requests\FCMTokenRequest;
use DB;
use Modules\User\Entities\FirebaseToken;

class UserController extends Controller
{
    use ApiResponseTrait;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('permission:user-view-permission', ['only' => ['index','show']]);
        // $this->middleware('permission:party-create-permission', ['only' => ['create', 'store']]);
        // $this->middleware('permission:party-edit-permission', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:party-delete-permission', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $per_page = $request->per_page ? $request->per_page : 10;
            $user = User::with('roleInfo', 'userAssignedRoles','department','userSource')
                    // ->userStatus()
                    ->filter($request->all())
                    ->sortable()
                    ->orderBy('id', 'desc')
                    ->paginate($per_page);
                        
            return $this->successResponse(200, 'All Users retrieved Successfully', $user);
        } catch (\Exception $ex) {
            \Log::error('user exception error: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(UserRequest $request)
    {
        try {
            $result = $this->userService->createUser($request->all());

            return $this->successResponse(200, 'User created successfully', $result);
        } catch (\Exception $ex) {
            \Log::error('user exception error: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        try {
            $user = User::with('roleInfo', 'userAssignedRoles','department','userSource')->find($id);

            return $this->successResponse(200, 'User retrieved successfully', $user);
        } catch (\Exception $ex) {
            \Log::error('user exception error: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $user = User::with('roleInfo', 'userAssignedRoles','department','userSource')->find($id);

            return $this->successResponse(200, 'User retrieved successfully', $user);
        } catch (\Exception $ex) {
            \Log::error('user exception error: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $result = $this->userService->updateUser($request->all(), $id);

            return $this->successResponse(200, 'User updated successfully', $result);
        } catch (\Exception $ex) {
            \Log::error('user exception error: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user?->delete();

            return $this->successResponse(200, 'User deleted successfully');
        } catch (\Exception $ex) {
            \Log::error('user exception error: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    public function fcmTokenUpdate(FCMTokenRequest $request)
    {
        DB::beginTransaction();
        try {
            $firebaseToken = FirebaseToken::where('device_id', $request->device_id)->first();
            if ($firebaseToken) {
                $firebaseToken->user_id = $request->user_id;
                $firebaseToken->fcm_token = $request->fcm_token;
                $firebaseToken->save();
            } else {
                $firebaseToken = new FirebaseToken();
                $firebaseToken->device_id = $request->device_id;
                $firebaseToken->user_id = $request->user_id;
                $firebaseToken->fcm_token = $request->fcm_token;
                $firebaseToken->save();
            }

            DB::commit();
            return $this->successResponse(200, "FCM Token Successfully added", $firebaseToken);
        } catch (\Exception $ex) {
            \Log::error('FCM token exception error: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }
}
