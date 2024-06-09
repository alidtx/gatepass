<?php

namespace Modules\Gatepass\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gatepass\Entities\GatepassType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Traits\ApiResponseTrait;
use Modules\Gatepass\Http\Requests\TypeRequest;

class GatepassTypeController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        // $this->middleware('permission:gatepass-type-view-permission', ['only' => ['index','show']]);
        // $this->middleware('permission:gatepass-type-create-permission', ['only' => ['create', 'store']]);
        // $this->middleware('permission:gatepass-type-edit-permission', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:gatepass-type-delete-permission', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $types = GatepassType::with('createdByUser')
                        ->active()
                        ->filter($request->all())
                        ->orderBy('id', 'desc')
                        ->sortable();

            $result = $request->per_page 
                        ? $types->paginate($request->per_page)
                        : $types->paginate(10);
            
            return $this->successResponse(200, 'All Type retrieved Successfully', $result);
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
    public function store(TypeRequest $request)
    {
        try {
            $data = new GatepassType();
            $data->name = $request->name;
            $data->status = $request->status;
            $data->created_by = Auth::user()->id;
            $data->save();

            return $this->successResponse(200, 'Type Stored Successfully', $data);
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
            $data = GatepassType::with('createdByUser')->find($id);

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
            $data = GatepassType::with('createdByUser')->find($id);

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
    public function update(TypeRequest $request, $id)
    {
        try {
            $data = GatepassType::find($id);
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
            $data = GatepassType::find($id);
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
