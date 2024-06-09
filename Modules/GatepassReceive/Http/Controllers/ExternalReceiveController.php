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
use App\Traits\AppHelperTrait;
use Modules\Gatecheck\Entities\Gatecheck;
use Modules\GatepassReceive\Entities\ExternalReceive;
use Modules\GatepassReceive\Entities\ExternalReceiveItem;
use Modules\GatepassReceive\Entities\ExternalReceiveDocument;
use Modules\GatepassReceive\Http\Requests\ExternalReceiveRequest;
use Modules\GatepassReceive\Http\Requests\GetReceiveNoRequest;
use Modules\GatepassReceive\Services\ExternalReceiveService;
use Modules\GatepassReceive\Transformers\ExternalReceiveResource;

class ExternalReceiveController extends Controller
{
    use ApiResponseTrait, AppHelperTrait;

    protected $externalReceiveService;

    public function __construct(ExternalReceiveService $externalReceiveService)
    {
        $this->externalReceiveService = $externalReceiveService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {

            $list = ExternalReceive::with(
                        'items', 
                        'toLocationInfo', 
                        'receivedByUser',
                        'toDepartmentInfo',
                        'documents'
                    )
                    ->orderBy('id','desc')        
                    ->sortable();
            $list = $request->per_page
                        ? $list->paginate($request->per_page)
                        : $list->paginate(10);

            ExternalReceiveResource::collection($list);
            return $this->successResponse(200, "List retrieved successfully", $list);
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
        return view('gatepassreceive::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ExternalReceiveRequest $request)
    {
        try {
            $response = $this->externalReceiveService->createExternalReceive($request->all());

            return $this->successResponse(200, 'Data stored successfully', $response);
            
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
        return view('gatepassreceive::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $details = ExternalReceive::with(
                            'items', 
                            'toLocationInfo', 
                            'receivedByUser',
                            'toDepartmentInfo', 
                            'documents'
                        )->find($id);

            return $this->successResponse(200, "Details Retrieved Successfully", $details);
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
    public function update(ExternalReceiveRequest $request, $id)
    {
        try {
            $result = $this->externalReceiveService->updateExternalReceive($request->all(), $id);

            return $this->successResponse(200, 'External Receive Update successfully', $result);
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
            $findItem = ExternalReceive::find($id);
            $findItem->delete();

            return $this->successResponse(200, "Data deleted successfully", null);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function storeDocument(Request $request)
    {
        try {
            if ($request->hasFile("file")) {
                $result = $this->processFile($request->file, 'external_receive');

                $documents = ExternalReceiveDocument::create([
                    'document_name' => $result,
                    'document' => 'storage/external_receive/' . $result
                ]);
            
                return $this->successResponse(200, 'Document Stored Successfully', $documents);
            }else {
                return $this->invalidResponse(422, 'Document Cannot Be Inserted', array('reason' => 'File not found'));
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $this->invalidResponse(500,'Data Cannot Be inserted Successfully', [$e->getMessage()]);
        }
    }

    public function getReceiveNo(GetReceiveNoRequest $request)
    {
        $result = $this->externalReceiveService->generateReceiveNo($request->receive_date_time);

        return $this->successResponse(200, 'Receive No Retrived', $result);
    }
}
