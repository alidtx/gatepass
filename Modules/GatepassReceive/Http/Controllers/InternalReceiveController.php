<?php

namespace Modules\GatepassReceive\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Utilities\Enums\InternalReceiveStatusEnum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 
use App\Traits\ApiResponseTrait;
use Modules\Gatecheck\Entities\Gatecheck;
use Modules\Gatepass\Entities\Gatepass;
use Modules\Gatepass\Entities\GatepassItem;
use Modules\GatepassReceive\Entities\InternalReceive;
use Modules\GatepassReceive\Http\Requests\InternalGatePassNoRequest;
use Modules\GatepassReceive\Http\Requests\InternalReceiveRequest;
use Modules\GatepassReceive\Services\InternalReceiveService;
use Modules\GatepassReceive\Transformers\InternalReceiveResource;

class InternalReceiveController extends Controller
{
    use ApiResponseTrait;
    
    protected $internalReceiveService;

    public function __construct(InternalReceiveService $internalReceiveService)
    {
        $this->internalReceiveService = $internalReceiveService;
        // $this->middleware('permission:gatepass-create-permission', ['only' => ['gatepassNoList']]);
        // $this->middleware('permission:internal-receive-view-permission', ['only' => ['index']]);
        // $this->middleware('permission:internal-receive-create-permission', ['only' => ['create', 'store']]);
        // $this->middleware('permission:internal-receive-edit-permission', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:internal-receive-delete-permission', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if(!auth()->user()->can('internal-receive-view-permission')) {
            try {

                $lists = InternalReceive::with(
                            'receivedItems', 
                            'toLocationInfo', 
                            'gatepassCheckInfo.gatepassInfo', 
                            'receivedByUser'
                        )->sortable();
                        
                $lists = $request->per_page
                            ? $lists->paginate($request->per_page)
                            : $lists->paginate(10);
                            
                InternalReceiveResource::collection($lists);
                return $this->successResponse(200, "List retrieved successfully", $lists);
            } catch(\Exception $e) {
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        } else {
            return $this->invalidResponse(403, ['Access Denied']);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('gatepassreceive::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(InternalReceiveRequest $request)
    {
        if(!auth()->user()->can('internal-receive-create-permission')) {
            try {
                $result = $this->internalReceiveService->createInternalReceive($request->all());

                return $this->successResponse(200, 'Internal Receive stored successfully', $result);
            } catch(\Exception $e) {
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        } else {
            return $this->invalidResponse(403, ['Access Denied']);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('gatepassreceive::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(!auth()->user()->can('internal-receive-edit-permission')) {
            try {
                $details = InternalReceive::with(
                            'receivedItems',
                            'toLocationInfo',
                            'gatepassCheckInfo.gatepassInfo', 
                            'receivedByUser'
                        )->find($id);

                return $this->successResponse(200, "Details Retrieved Successfully", $details);
            } catch(\Exception $e) {
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        } else {
            return $this->invalidResponse(403, ['Access Denied']);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(InternalReceiveRequest $request, $id)
    {
        if(!auth()->user()->can('internal-receive-edit-permission')) {
            try {
                $result = $this->internalReceiveService->update($request->all(), $id);

                return $this->successResponse(200, 'Internal Receive Update successfully', $result);
            } catch(\Exception $e) {
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        } else {
            return $this->invalidResponse(403, ['Access Denied']);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(!auth()->user()->can('internal-receive-delete-permission')) {
            try {
                $findItem = InternalReceive::find($id);
                $findItem->delete();

                return $this->successResponse(200, "Data deleted successfully", null);
            } catch(\Exception $e) {
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        } else {
            return $this->invalidResponse(403, ['Access Denied']);
        }
    }

    public function gatepassNoList(InternalGatePassNoRequest $request)
    {
        try {
            $list = Gatecheck::with('fromLocationInfo','gatepassInfo','releasedUser','internalReceived')
                        ->released()
                        ->filter($request->all())
                        ->orderBy('id','desc')
                        ->get();

            return $this->successResponse(200, 'Internal Released Gatepass List Retrieved', $list);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}
