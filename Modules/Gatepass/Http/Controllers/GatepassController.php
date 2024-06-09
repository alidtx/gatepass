<?php

namespace Modules\Gatepass\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Gatepass\Entities\Gatepass;
use Modules\Gatepass\Entities\GatepassItem;
use Modules\Gatepass\Entities\GatepassDocument;
use Modules\Gatepass\Services\GatepassService;
use Modules\Gatepass\Http\Requests\GatepassRequest;
use Modules\Gatepass\Http\Requests\BulkApprovalRequest;
use App\Traits\AppHelperTrait;
use DB;
use App\Models\User;
use Modules\Gatepass\Entities\GatepassType;

class GatepassController extends Controller
{
    use ApiResponseTrait, AppHelperTrait;

    protected $gatepassService;

    public function __construct(GatepassService $gatepassService)
    {
        $this->gatepassService = $gatepassService;
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->hasRole(['Super Admin', 'GM', 'Production Head','Security Guard'])) {
                $gatepasses = Gatepass::query();
            } elseif ($user->hasRole('HOD')) {
                $departmentId = $user->department_id;
                $userIds = User::userStatus()->where('department_id', $departmentId)->pluck('id');
                $gatepasses = Gatepass::whereIn('created_by', $userIds);
            } elseif ($user->hasRole('Employee')) {
                $gatepasses = Gatepass::where('created_by', $user->id);
            }

            $per_page = $request->per_page ? $request->per_page : 10;
            $gatepass = $gatepasses->with([
                'typeInfo:id,name',
                'toLocationInfo:id,name', 
                'party:id,name,address,phone', 
                'createdByUser:id,name,email,department_id',
                'createdByUser.department:id,name',
                'approvedBy:id,name,email,department_id',
                'approvedBy.department:id,name',
                'secondApprovedBy:id,name,email,department_id', 
                'secondApprovedBy.department:id,name',
                'toPersonInfo:id,name,email,department_id', 
                'toPersonInfo.department:id,name',
                'toPersonDepartmentInfo:id,name', 
                'fromLocationInfo:id,name',
                'items:id,gatepass_id,item_id,item_description,unit_id,qty',
                'items.itemInfo:id,unit_id,name',
                'items.unitInfo:id,name',
                'items.itemInfo.unitInfo:id,name',
                'documents'
            ]);

            $gatepass = $gatepass->filter($request->all())
                        ->orderBy('id', 'desc')
                        ->sortable()
                        ->paginate($per_page);

            $this->includeCreatedByDeptHod($gatepass);

            return $this->successResponse(200, 'All Gate Pass retrieved Successfully', $gatepass);
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
    public function store(GatepassRequest $request)
    {
        try {
            $result = $this->gatepassService->createGatepass($request->all());

            return $this->successResponse(200, 'Gatepass stored successfully', $result);
        
        } catch (\Exception $e) {
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
            $gatepass = Gatepass::with('createdByUser','items', 'items.unitInfo','documents','party')->where('id', $id)->first();
            
            return $this->successResponse(200, 'Gate Pass retrieved Successfully', $gatepass);
        } catch (\Exception $ex) {
            \Log::error('Type List Exception: '. $ex->getMessage());
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
            $gatepass = Gatepass::with('createdByUser', 'items', 'items.unitInfo','documents','party')->where('id', $id)->first();
            
            return $this->successResponse(200, 'Gate Pass retrieved Successfully', $gatepass);
        } catch (\Exception $ex) {
            \Log::error('Type List Exception: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(GatepassRequest $request, $id)
    {
        try {
            $gatepass = Gatepass::find($id);

            // Check if the gatepass has been internally received
            if ($gatepass->gatecheck()->exists() && $gatepass->gatecheck->internalReceived()->exists()) {
                return $this->invalidResponse(422, 'Gatepass cannot be updated because it has been received internally');
            }

            $result = $this->gatepassService->updateGatepass($request->all(), $id);

            return $this->successResponse(200, 'Gatepass Updated successfully', $result);
        
        } catch (\Exception $e) {
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
        if(auth()->user()->can('gatepass-delete-permission')) {
            try {
                $findItem = Gatepass::find($id);
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

    public function storeDocument(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->hasFile("file")) {

                $result = $this->processFile($request->file, 'gatepass_document');

                $documents = GatepassDocument::create([
                    'document_name' => $result,
                    'document' => 'storage/gatepass_document/' . $result
                ]);

                DB::commit();
                return $this->successResponse(200, 'Document Stored Successfully', $documents);
            }else {
                return $this->invalidResponse(422, 'Document Cannot Be Inserted', array('reason' => 'File not found'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            return $this->invalidResponse(500,'Data Cannot Be inserted Successfully', [$e->getMessage()]);
        }
    }

    public function approve(Request $request, $id)
    {
        if (!Auth::user()->hasRole(['HOD'])) {
            return $this->invalidResponse(403, ['Access denied!']);
        } else {
            DB::beginTransaction();
            try {
                $data = Gatepass::find($id);
                $data->approved_by = Auth::user()->id;
                $data->approval_datetime = $request->approval_datetime;
                $data->status = $request->status; // 2=approved,3=rejected
                $data->save();

                $gatepassType = $data->typeInfo->name;
                if($gatepassType && $gatepassType == 'Internal' && $request->status == 2) {
                    $data->status = 4;
                    $data->second_approved_by = Auth::user()->id;
                    $data->second_approval_datetime = $request->approval_datetime;
                    $data->save();
                }

                if($data?->status == 2) {
                    // send approval notification to GM/Production Head role users
                    // send rejection notification to Gatepass Creator
                    $this->gatepassService->sendApprovalNotification($data, $request->status);
                }

                DB::commit();
                return $this->successResponse(200, 'Gatepass Status Updated Successfully', $data);
            } catch(\Exception $e) {
                DB::rollBack();
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        }
    }
    

    public function finalApprove(Request $request, $id)
    {
        if (!Auth::user()->hasRole(['GM', 'Production Head'])) {
            return $this->invalidResponse(403, ['Access denied!']);
        } else {
            DB::beginTransaction();
            try {
                $data = Gatepass::find($id);
                $data->second_approved_by = Auth::user()->id;
                $data->second_approval_datetime = $request->second_approval_datetime;
                $data->status = $request->status; // 4=finally-approved,3=rejected
                $data->save();
                
                // send approval/rejection notification to Gatepass Creator
                $this->gatepassService->sendFinalApprovalNotification($data, $request->status);
                
                DB::commit();
                return $this->successResponse(200, 'Gatepass Status Updated Successfully', $data);
            } catch(\Exception $e) {
                DB::rollBack();
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        }
    }

    public function bulkApprove(BulkApprovalRequest $request)
    {
        if (!Auth::user()->hasRole(['HOD'])) {
            return $this->invalidResponse(403, ['Access denied!']);
        } else {
            DB::beginTransaction();
            $statusText = $request->status==2 ? 'approved' : 'rejected';
            try {
                Gatepass::whereIn('id', $request->id)->update([
                    'status' => $request->status,
                    'approval_datetime' => $request->approval_datetime,
                    'approved_by' => Auth::user()->id
                ]);

                // Find the gatepasses whose type is Internal and then perform 2nd approve automatically if requuest status is 2
                $internalTypeId = GatepassType::where('name', 'Internal')->value('id');
                if ($request->status == 2) {
                    Gatepass::whereIn('id', $request->id)
                        ->where('type_id', $internalTypeId)
                        ->update([
                            'status' => 4, 
                            'second_approved_by' => Auth::user()->id, 
                            'second_approval_datetime' => $request->approval_datetime
                        ]);

                    $updatedForSecondApprovalIds = Gatepass::whereIn('id', $request->id)
                        ->where('type_id', $internalTypeId)
                        ->pluck('id')
                        ->toArray();
                }

                $remainingIds = array_diff($request->id, $updatedForSecondApprovalIds);
                
                // send approval notification to GM/Production Head role users
                // send rejection notification to Gatepass Creator
                $gatepasses = Gatepass::whereIn('id', $remainingIds)->pluck('gate_pass_no')->toArray();
                $this->gatepassService->sendApprovalNotification($gatepasses, $request->status, 'bulk');

                DB::commit();
                return $this->successResponse(200, 'All selected items has been '.$statusText.' successfully', null);
            } catch(\Exception $e) {
                DB::rollBack();
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        }
    }
    
    public function bulkFinalApprove(Request $request)
    {
        if (!Auth::user()->hasRole(['GM', 'Production Head'])) {
            return $this->invalidResponse(403, ['Access denied!']);
        } else {
            DB::beginTransaction();
            $statusText = $request->status==4 ? 'fully approved' : 'rejected';
            try {
                Gatepass::whereIn('id', $request->id)->update([
                    'status' => $request->status,
                    'second_approval_datetime' => $request->second_approval_datetime,
                    'second_approved_by' => Auth::user()->id
                ]);

                $gatepasses = Gatepass::whereIn('id', $request->id)->pluck('gate_pass_no')->toArray();

                // send approval/rejection notification to Gatepass Creator
                $this->gatepassService->sendFinalApprovalNotification($gatepasses, $request->status, 'bulk');
                
                DB::commit();
                return $this->successResponse(200, 'All selected items has been '.$statusText.' successfully', null);
            } catch(\Exception $e) {
                DB::rollBack();
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        }
    }
    
    public function includeCreatedByDeptHod($gatepass)
    {
        foreach ($gatepass as $gp) {
            $hodUser = User::whereHas('department', function ($query) use ($gp) {
                $query->where('id', $gp?->createdByUser?->department_id);
            })->whereHas('userAssignedRoles', function ($query) {
                $query->whereHas('roleInfo', function ($query) {
                    $query->where('name', 'HOD');
                });
            })->orderByDesc('id')->first();
            $gp->createdByUserDepartmentHod = $hodUser ? $hodUser->name : null;
        }
    }
}
