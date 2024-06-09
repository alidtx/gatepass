<?php

namespace Modules\SecurityLightOnOffRegister\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SecurityLightOnOffRegister\Entities\SecurityLightOnOffRegister;
use App\Traits\ApiResponseTrait;
class SecurityLightOnOffRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $KeyControl=SecurityLightOnOffRegister::orderBy('id', 'desc')
            ->orderBy('id', 'desc')
            ->sortable();
            $result = $request->per_page 
            ? $KeyControl->paginate($request->per_page)
            : $KeyControl->paginate(10);
            return $this->successResponse(200, 'Data Retrieved Successfully', $result);
              
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
        return view('securitylightonoffregister::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = new SecurityLightOnOffRegister();
            $data->date = $request->date;
            $data->designation = $request->designation;
            $data->name = $request->name;
            $data->on_time = $request->on_time;
            $data->off_time = $request->off_time;
            $data->security_signature = $request->security_signature;
            $data->security_officer_signature = $request->security_officer_signature;
            $data->save();          
            return $this->successResponse(200, 'Data Stored Successfully', $data);

        }catch (\Exception $ex) {
            \Log::error('Type List Exception: '. $ex->getMessage());
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
        return view('securitylightonoffregister::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $data = SecurityLightOnOffRegister::find($id);
            return $this->successResponse(200, 'Data Retrieved Successfully', $data);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
        return $this->successResponse(200, 'Data Retrieved Successfully', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            $data =SecurityLightOnOffRegister::find($id);
            $data->date = $request->date;
            $data->designation = $request->designation;
            $data->name = $request->name;
            $data->on_time = $request->on_time;
            $data->off_time = $request->off_time;
            $data->security_signature = $request->security_signature;
            $data->security_officer_signature = $request->security_officer_signature;
            $data->save();
            return $this->successResponse(200, 'Data updated Successfully', $data);

        }catch (\Exception $ex) {
            \Log::error('Type List Exception: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
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
            $data=SecurityLightOnOffRegister::find($id);
            if($data)  {
              $data->delete();
            }
            return $this->successResponse(200, 'Data deleted successfully');
        }catch (\Exception $ex) {
            \Log::error('Type List Exception: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        } 
    }
}
