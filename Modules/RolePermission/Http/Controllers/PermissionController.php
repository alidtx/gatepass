<?php

namespace Modules\RolePermission\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RolePermission\Http\Requests\PermissionRequest;
use Spatie\Permission\Models\Permission;
use App\Traits\ApiResponseTrait;

class PermissionController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $per_page = $request->per_page ? $request->per_page : 10;
            $permissions = Permission::query();
            if($request->name) {
                $permissions = $permissions->where('name', 'like', '%'.$request->name.'%');
            } 
            
            $permissions = $permissions->orderBy('id','desc')->paginate($per_page);

            return $this->successResponse(200, 'All Permissions retrieved Successfully', $permissions);
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
    public function store(PermissionRequest $request)
    {
        try {
            $result = Permission::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            return $this->successResponse(200, 'Permission created successfully', $result);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
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
            $permission = Permission::find($id);
            return $this->successResponse(200, 'Permission retrieved successfully', $permission);
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
            $permission = Permission::find($id);
            return $this->successResponse(200, 'Permission retrieved successfully', $permission);
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
    public function update(PermissionRequest $request, $id)
    {
        try {
            $result = Permission::find($id);
            $result->name = $request->name;
            $result->save();

            return $this->successResponse(200, 'Permission updated successfully', $result);
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
            $permission = Permission::find($id);
            $permission?->delete();

            return $this->successResponse(200, 'Permission deleted successfully');
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}
