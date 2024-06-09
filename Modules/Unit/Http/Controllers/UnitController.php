<?php

namespace Modules\Unit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Traits\ApiResponseTrait;
use Modules\Unit\Entities\Unit;
use Modules\Unit\Http\Requests\UnitRequest;

class UnitController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:unit-view-permission', ['only' => ['index','show']]);
        // $this->middleware('permission:unit-create-permission', ['only' => ['create', 'store']]);
        // $this->middleware('permission:unit-edit-permission', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:unit-delete-permission', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $units = Unit::with('createdByUser')
                    ->active()
                    ->filter($request->all())
                    ->orderBy('id', 'desc')
                    ->sortable();

            $units = $request->per_page 
                        ? $units->paginate($request->per_page)
                        : $units->paginate(10);
            
            return $this->successResponse(200, 'All Locations retrieved Successfully', $units);
        } catch (\Exception $ex) {
            \Log::error('Type List Exception: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('gatepass::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(UnitRequest $request)
    {
        try {
            $data = new Unit();
            $data->name = $request->name;
            $data->status = $request->status;
            $data->created_by = Auth::user()->id;
            $data->save();

            return $this->successResponse(200, 'Data Stored Successfully', $data);
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
            $data = Unit::with('createdByUser')->find($id);

            return $this->successResponse(200, 'Data Retrieved Successfully', $data);
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
            $data = Unit::with('createdByUser')->find($id);

            return $this->successResponse(200, 'Data Retrieved Successfully', $data);
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
    public function update(UnitRequest $request, $id)
    {
        try {
            $data = Unit::find($id);
            $data->name = $request->name;
            $data->status = $request->status;
            $data->save();

            return $this->successResponse(200, 'Data updated successfully', $data);
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
            $data = Unit::find($id);
            if($data) {
                $data->delete();
            }

            return $this->successResponse(200, 'Data deleted successfully');
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}
