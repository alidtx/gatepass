<?php

namespace Modules\RentMachine\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RentMachine\Entities\RentMachine;
use App\Traits\ApiResponseTrait;
use Modules\RentMachine\Http\Requests\RentMachineRequest;
class RentMachineController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {
            $RentMachine=RentMachine::orderBy('id', 'desc')
            ->orderBy('id', 'desc')
            ->sortable();

            $result = $request->per_page 
            ? $RentMachine->paginate($request->per_page)
            : $RentMachine->paginate(10);
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
        return view('rentmachine::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = new RentMachine();
            $data->date = $request->date;
            $data->time = $request->time;
            $data->gatepass_no = $request->gatepass_no;
            $data->challan_no = $request->challan_no;
            $data->party = $request->party;
            $data->in_date = $request->in_date;
            $data->times = $request->times;
            $data->machine_name = $request->machine_name;
            $data->sl_no = $request->sl_no;
            $data->qty = $request->qty;
            $data->security_sign = $request->security_sign;
            $data->depositor_signature = $request->depositor_signature;
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
        return view('rentmachine::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request,$id)
    {
        try {
            $data = RentMachine::find($id);
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
            $data =RentMachine::find($id);
            $data->date = $request->date;
            $data->time = $request->time;
            $data->gatepass_no = $request->gatepass_no;
            $data->challan_no = $request->challan_no;
            $data->party = $request->party;
            $data->in_date = $request->in_date;
            $data->times = $request->times;
            $data->machine_name = $request->machine_name;
            $data->sl_no = $request->sl_no;
            $data->qty = $request->qty;
            $data->security_sign = $request->security_sign;
            $data->depositor_signature = $request->depositor_signature;
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
            $data=RentMachine::find($id);
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
