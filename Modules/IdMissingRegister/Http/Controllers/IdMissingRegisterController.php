<?php

namespace Modules\IdMissingRegister\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\IdMissingRegister\Entities\IdMissingRegister; 
use App\Traits\ApiResponseTrait;
class IdMissingRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $IdMissingRegister=IdMissingRegister::orderBy('id', 'desc')
            ->orderBy('id', 'desc')
            ->sortable();

            $result = $request->per_page 
            ? $IdMissingRegister->paginate($request->per_page)
            : $IdMissingRegister->paginate(10);
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
        return view('idmissingregister::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = new IdMissingRegister();
            $data->date_time = $request->date_time;
            $data->card_type = $request->card_type;
            $data->card_no = $request->card_no;
            $data->reporter_name = $request->reporter_name;
            $data->reporter_sign = $request->reporter_sign;
            $data->card_release_date = $request->card_release_date;
            $data->security_officer_sign = $request->security_officer_sign;
            $data->admin_manager_sign = $request->admin_manager_sign;
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
        return view('idmissingregister::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $data = IdMissingRegister::find($id);
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
            $data =IdMissingRegister::find($id);
            $data->date_time = $request->date_time;
            $data->card_type = $request->card_type;
            $data->card_no = $request->card_no;
            $data->reporter_name = $request->reporter_name;
            $data->reporter_sign = $request->reporter_sign;
            $data->card_release_date = $request->card_release_date;
            $data->security_officer_sign = $request->security_officer_sign;
            $data->admin_manager_sign = $request->admin_manager_sign;
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
            $data=IdMissingRegister::find($id);
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
