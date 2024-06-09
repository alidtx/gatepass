<?php

namespace Modules\MedicalRoom\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\MedicalRoom\Entities\MedicalRoom;
use App\Traits\ApiResponseTrait;
class MedicalRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $MedicalRoom=MedicalRoom::orderBy('id', 'desc')
            ->orderBy('id', 'desc')
            ->sortable();
            $result = $request->per_page 
            ? $MedicalRoom->paginate($request->per_page)
            : $MedicalRoom->paginate(10);
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
        return view('medicalroom::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {       
        try {
           
            $data = new MedicalRoom();
            $data->date = $request->date;
            $data->out_time = $request->out_time;
            $data->gatepass_no = $request->gatepass_no;
            $data->party_name = $request->party_name;
            $data->goods_name = $request->goods_name;
            $data->unit = $request->unit;
            $data->qty = $request->qty;
            $data->security_sign = $request->security_sign;
            $data->security_officer_sign = $request->security_officer_sign;
            $data->comment = $request->comment;
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
        return view('medicalroom::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request,$id)
    {
        try {
            $data = MedicalRoom::find($id);
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
            
            $data =MedicalRoom::find($id);
            $data->date = $request->date;
            $data->out_time = $request->out_time;
            $data->gatepass_no = $request->gatepass_no;
            $data->party_name = $request->party_name;
            $data->goods_name = $request->goods_name;
            $data->unit = $request->unit;
            $data->qty = $request->qty;
            $data->security_sign = $request->security_sign;
            $data->security_officer_sign = $request->security_officer_sign;
            $data->comment = $request->comment;
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
            $data=MedicalRoom::find($id);
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
