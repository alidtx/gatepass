<?php

namespace Modules\SecurityPatrolDuty\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SecurityPatrolDuty\Entities\SecurityPatrolDuty;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
class SecurityPatrolDutyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $SecurityPatrolDuty=SecurityPatrolDuty::orderBy('id', 'desc')
            ->orderBy('id', 'desc')
            ->sortable();

            $result = $request->per_page 
            ? $SecurityPatrolDuty->paginate($request->per_page)
            : $SecurityPatrolDuty->paginate(10);
            return $this->successResponse(200, 'All Security Patrol retrieved Successfully', $result);
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
        return view('securitypatrolduty::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = new SecurityPatrolDuty();
        $data->date = $request->date;
        $data->name = Auth::user()->name;
        $data->designation = $request->designation;
        $data->from = $request->from;
        $data->to = $request->to;
        $data->is_permital_ok = $request->is_permital_ok;
        $data->is_flatlight_ok = $request->is_flatlight_ok;
        $data->is_camera_ok = $request->is_camera_ok;
        $data->is_window_ok = $request->is_window_ok;
        $data->is_security_gaurd_ok = $request->is_security_gaurd_ok;
        $data->duty_description = $request->duty_description;
        $data->security_name = $request->security_name;
        $data->security_officer_name = $request->security_officer_name;
        $data->permital_box = $request->permital_box;
        $data->flatlight_box = $request->flatlight_box;
        $data->camera_box = $request->camera_box;
        $data->window_box = $request->window_box;
        $data->security_box = $request->security_box;
        $data->save();
        return $this->successResponse(200, 'Data Stored Successfully', $data);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('securitypatrolduty::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request,$id)
    {
        try {
            $data = SecurityPatrolDuty::find($id);
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
    public function update(Request $request, $id)
    {
        $data =SecurityPatrolDuty::find($id);
        $data->date = $request->date;
        $data->name = Auth::user()->name;
        $data->designation = $request->designation;
        $data->from = $request->from;
        $data->to = $request->to;
        $data->is_permital_ok = $request->is_permital_ok;
        $data->is_flatlight_ok = $request->is_flatlight_ok;
        $data->is_camera_ok = $request->is_camera_ok;
        $data->is_window_ok = $request->is_window_ok;
        $data->is_security_gaurd_ok = $request->is_security_gaurd_ok;
        $data->duty_description = $request->duty_description;
        $data->security_name = $request->security_name;
        $data->security_officer_name = $request->security_officer_name;
        $data->permital_box = $request->permital_box;
        $data->flatlight_box = $request->flatlight_box;
        $data->camera_box = $request->camera_box;
        $data->window_box = $request->window_box;
        $data->security_box = $request->security_box;
        $data->save();
        return $this->successResponse(200, 'Data updated Successfully', $data);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $data=SecurityPatrolDuty::find($id);
        if($data)  {
          $data->delete();
        }
        return $this->successResponse(200, 'Data deleted successfully');
    }
}
