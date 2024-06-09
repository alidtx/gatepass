<?php

namespace Modules\SecurityDutyRegister\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SecurityDutyRegister\Entities\SecurityDutyRegister;
use App\Traits\ApiResponseTrait;
class SecurityDutyRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $KeyControl=SecurityDutyRegister::orderBy('id', 'desc')
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
        
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {      
        //  dd($request->all());  
        try {
            $data = new SecurityDutyRegister();
            $data->id_no = $request->id_no;
            $data->rank = $request->rank;
            $data->name = $request->name;
            $data->post = $request->post;
            $data->from = $request->from;
            $data->to = $request->to;
            $data->hours = $request->hours;
            $data->sat = $request->sat;
            $data->sun = $request->sun;
            $data->mon = $request->mon;
            $data->tues = $request->tues;
            $data->wed = $request->wed;
            $data->thu = $request->thu;
            $data->friday = $request->friday;
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
        return view('securitydutyregister::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $data = SecurityDutyRegister::find($id);
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
            $data = new SecurityDutyRegister();
            $data->id_no = $request->id_no;
            $data->rank = $request->rank;
            $data->name = $request->name;
            $data->post = $request->post;
            $data->from = $request->from;
            $data->to = $request->to;
            $data->hours = $request->hours;
            $data->sat = $request->sat;
            $data->sun = $request->sun;
            $data->mon = $request->mon;
            $data->tues = $request->tues;
            $data->wed = $request->wed;
            $data->thu = $request->thu;
            $data->friday = $request->friday;
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
            $data=SecurityDutyRegister::find($id);
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
