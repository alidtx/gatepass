<?php

namespace Modules\Party\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Traits\ApiResponseTrait;
use Modules\Party\Entities\Party;
use Modules\Party\Http\Requests\PartyRequest;

class PartyController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        // $this->middleware('permission:party-view-permission', ['only' => ['index','show']]);
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
            $party = Party::with('createdByUser')
                        ->active()
                        ->filter($request->all())
                        ->orderBy('id', 'desc')
                        ->sortable();

            $result = $request->per_page 
                        ? $party->paginate($request->per_page)
                        : $party->paginate(10);
            
            return $this->successResponse(200, 'All Parties retrieved Successfully', $result);
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
    public function store(PartyRequest $request)
    {
        try {
            $party = new Party();
            $party->name = $request->name;
            $party->status = $request->status;
            $party->address = $request->address;
            $party->phone = $request->phone;
            $party->created_by = auth()->user()->id;
            $party->save();

            return $this->successResponse(200, 'Party Stored Successfully', $party);
        } catch (\Exception $ex) {
            \Log::error('Party Store Exception: '. $ex->getMessage());
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
            $data = Party::with('createdByUser')->find($id);

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
            $data = Party::with('createdByUser')->find($id);

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
    public function update(PartyRequest $request, $id)
    {
        try {
            $data = Party::find($id);
            $data->name = $request->name;
            $data->status = $request->status;
            $data->address = $request->address;
            $data->phone = $request->phone;
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
            $data = Party::find($id);
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
