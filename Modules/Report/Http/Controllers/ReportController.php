<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Traits\AppHelperTrait;
use Modules\GatepassReceive\Entities\InternalReceive;
use Carbon\Carbon;
use Modules\Report\Transformers\InternalReceiveReportResource;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Exports\GatepassReportExport;
use Modules\Report\Exports\InternalGatepassReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Gatepass\Entities\Gatepass;
use App\Models\User;

class ReportController extends Controller
{
    use ApiResponseTrait, AppHelperTrait;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function getInternalReceivedGatepasses(Request $request)
    {
        if (Auth::user()->can('internal-receive-gatepass-report-view-permission')) {
            return $this->invalidResponse(403, ['Access denied!']);
        } else {
            try {
                $per_page = $request->per_page ? $request->per_page:10;
                // Retrieve data based on date range and location IDs and order by id descending
                $gatepasses = InternalReceive::with('receivedItems', 'gatepassCheckInfo.gatepassInfo', 'gatepassCheckInfo.fromLocationInfo','toLocationInfo')
                    ->when($request->has('from_date') && $request->has('to_date'), function ($query) use ($request) {
                        return $query->customDateRange($request->from_date, $request->to_date);
                    }, function ($query) {
                        return $query->currentMonth();
                    })
                    ->when($request->has('gatepass_no'), function ($query) use ($request) {
                        return $query->whereHas('gatepassCheckInfo.gatepassInfo', function ($query) use ($request) {
                            return $query->where('gate_pass_no', 'like', '%' . $request->gatepass_no. '%');
                        });
                    })
                    ->when($request->has('from_location_id'), function ($query) use ($request) {
                        return $query->whereHas('gatepassCheckInfo', function ($query) use ($request) {
                            return $query->where('from_location_id', $request->from_location_id);
                        });
                    })
                    ->when($request->has('to_location_id'), function ($query) use ($request) {
                        return $query->where('to_location_id', $request->to_location_id);
                    })
                    ->orderBy('id', 'desc')
                    ->paginate($per_page);

                InternalReceiveReportResource::collection($gatepasses);
                return $this->successResponse(200, 'All Gate Pass retrieved Successfully', $gatepasses);
            } catch (\Exception $ex) {
                \Log::error('Type List Exception: '. $ex->getMessage());
                throw new \Exception($ex->getMessage());
            }
        }
    }

    public function downloadInternalGatepassExcel()
    {
        // if (Auth::user()->can('internal-receive-gatepass-report-download-permission')) {
        //     return $this->invalidResponse(403, ['Access denied!']);
        // } else {
            try {
                return Excel::download(new InternalGatepassReportExport, 'gatepass_report_'.now().'.xlsx');
            } catch (\Exception $ex) {
                \Log::error('Type List Exception: '. $ex->getMessage());
                throw new \Exception($ex->getMessage());
            }
        // } 
    }

    public function getGatepassReport()
    {
        // if (Auth::user()->can('gatepass-report-download-permission')) {
        //     return $this->invalidResponse(403, ['Access denied!']);
        // } else {
            try {
                return Excel::download(new GatepassReportExport, 'gatepass_report_'.now().'.xlsx');
            } catch (\Exception $ex) {
                \Log::error('Type List Exception: '. $ex->getMessage());
                throw new \Exception($ex->getMessage());
            }
        // }
    }

    public function downloadGatepassReport(Request $request, $id)
    {
        // if (Auth::user()->can('gatepass-report-download-permission')) {
        //     return $this->invalidResponse(403, ['Access denied!']);
        // } else {
            try {
                $gatepass = Gatepass::with([
                    'items',
                    'createdByUser',
                    'secondApprovedBy'
                ])
                ->find($id);

            $createdByUser = auth()->user();
            $departmentId = $gatepass->createdByUser?->department?->id;
            $secondApproveByDepartmentId = $gatepass?->secondApprovedBy?->department?->id;
            $secondApprovedRoleName = $gatepass?->secondApprovedBy?->userAssignedRoles?->first()?->roleInfo?->name;
            $imageFile = $secondApprovedRoleName == 'GM' ? 'images/gm_digital_sign.jpg' : ($secondApprovedRoleName == 'Production Head' ? 'images/production_head_digital_sign.jpg' : null);

            $hodUserName = User::whereHas('department', function ($query) use ($departmentId) {
                                $query->where('id', $departmentId);
                            })->whereHas('userAssignedRoles', function ($query) {
                                $query->whereHas('roleInfo', function ($query) {
                                    $query->where('name', 'HOD');
                                });
                            })->orderByDesc('id')->value('name');

            $gatepass->hodUserName = $hodUserName;
            $gatepass->imageFile = $imageFile;

            // Load the view from the Report module
            $view = view('report::single-gatepass-pdf', compact('gatepass'))->render();

            // Generate PDF using dompdf
            $pdf = PDF::loadHTML($view);

            return $pdf->download('gatepass_'.$gatepass->gate_pass_no);
            } catch (\Exception $ex) {
                \Log::error('Single Gatepass PDF Report Exception: '. $ex->getMessage());
                throw new \Exception($ex->getMessage());
            }
        // }
    }
}
