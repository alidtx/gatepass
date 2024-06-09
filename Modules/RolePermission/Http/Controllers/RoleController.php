<?php

namespace Modules\RolePermission\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 
use App\Traits\ApiResponseTrait;
use Modules\RolePermission\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        // $this->middleware('permission:role-view-permission', ['only' => ['index', 'show']]);
        // $this->middleware('permission:role-create-permission', ['only' => ['create', 'store']]);
        // $this->middleware('permission:role-edit-permission', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:role-delete-permission', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $per_page = $request->per_page ? $request->per_page : 10;
            $list = Role::with('permissions')->orderBy('id', 'desc');
            if($request->name) {
                $list = $list->where('name', 'like', '%'.$request->name.'%');
            }
            $list = $list->paginate($per_page);


            return $this->successResponse(200, "Role List retrieved successdfully", $list);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('rolepermission::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(RoleRequest $request)
    {
        try{
            $result = Role::create([
                'name' => $request->name, 
                'guard_name' => 'web', 
                'status' => 1
            ]);
            
            $result->syncPermissions($request->permissions);

            return $this->successResponse(200, 'Role Stored Successfully.', $result);

        }catch (\Exception $e){
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
            $item = Role::with("permissions")->find($id);

            return $this->successResponse(200, 'Role Details retrieved', $item);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
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
            $item = Role::with("permissions")->find($id);

            return $this->successResponse(200, 'Role Details retrieved', $item);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(RoleRequest $request, $id)
    {
        try {
            $role = Role::find($id);
            $role->name = $request->name;
            $role->save();

            $permissions = $request->permissions;

            // Sync permissions
            $role->syncPermissions($permissions);

            return $this->successResponse(200, 'Role Updated Successfully.', $role);

        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
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
            $item = Role::where('id', $id)->delete();

            return $this->successResponse(200, 'Role Deleted Successfully');
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}
