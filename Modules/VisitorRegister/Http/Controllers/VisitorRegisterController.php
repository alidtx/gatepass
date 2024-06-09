<?php

namespace Modules\VisitorRegister\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Traits\ApiResponseTrait;
use Modules\VisitorRegister\Entities\VisitorRegister;
class VisitorRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $VisitorRegister=VisitorRegister::orderBy('id', 'desc')
            ->orderBy('id', 'desc')
            ->sortable();

            $result = $request->per_page 
            ? $VisitorRegister->paginate($request->per_page)
            : $VisitorRegister->paginate(10);
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
        return view('visitorregister::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = new VisitorRegister();
            $data->visit_date = $request->visit_date;
            $data->visit_reason = $request->visit_reason;
            $data->visitor_name = $request->visitor_name;
            $data->visitor_contact = $request->visitor_contact;
            $data->visitor_def = $request->visitor_def;
            $data->visitor_address = $request->visitor_address;
            $data->in_time = $request->in_time;
            $data->out_time = $request->out_time;
            $data->visitor_issue_id = $request->visitor_issue_id;
            $data->photo_id = $request->photo_id;
            $data->body = $request->body;
            $data->bag = $request->bag;
            $data->ok = $request->ok;
            $data->no= $request->no;
            $data->return = $request->return;
            $data->visitor = $request->visitor;
            $data->received_by = $request->received_by;
            $data->incharge = $request->incharge;
            $data->admin_sign = $request->admin_sign;
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
        return view('visitorregister::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request,$id)
    {
        try {
            $data = VisitorRegister::find($id);
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
            $data = VisitorRegister::find($id);
            $data->visit_date = $request->visit_date;
            $data->visit_reason = $request->visit_reason;
            $data->visitor_name = $request->visitor_name;
            $data->visitor_contact = $request->visitor_contact;
            $data->visitor_def = $request->visitor_def;
            $data->visitor_address = $request->visitor_address;
            $data->in_time = $request->in_time;
            $data->out_time = $request->out_time;
            $data->visitor_issue_id = $request->visitor_issue_id;
            $data->photo_id = $request->photo_id;
            $data->body = $request->body;
            $data->bag = $request->bag;
            $data->ok = $request->ok;
            $data->no= $request->no;
            $data->return = $request->return;
            $data->visitor = $request->visitor;
            $data->received_by = $request->received_by;
            $data->incharge = $request->incharge;
            $data->admin_sign = $request->admin_sign;
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
            $data=VisitorRegister::find($id);
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
