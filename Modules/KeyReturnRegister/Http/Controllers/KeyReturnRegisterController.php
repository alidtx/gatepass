<?php

namespace Modules\KeyReturnRegister\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\KeyReturnRegister\Entities\KeyReturnRegister;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
class KeyReturnRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $KeyControl=KeyReturnRegister::orderBy('id', 'desc')
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
        return view('keyreturnregister::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = new KeyReturnRegister();
            $data->date = $request->date;
            $data->number_of_key = $request->number_of_key;
            $data->type = $request->type;
            $data->deliver_time = $request->deliver_time;
            $data->provider_name = $request->provider_name;
            $data->provider_designation = $request->provider_designation;
            $data->provider_signature = $request->provider_signature;
            $data->reciever_name = $request->reciever_name;
            $data->reciever_designation = $request->reciever_designation;
            $data->reciever_signature = $request->reciever_signature;
            $data->security_officer = Auth::user()->name;
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
        return view('keyreturnregister::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $data = KeyReturnRegister::find($id);
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
        try {
            $data =KeyReturnRegister::find($id);
            $data->date = $request->date;
            $data->number_of_key = $request->number_of_key;
            $data->type = $request->type;
            $data->deliver_time = $request->deliver_time;
            $data->provider_name = $request->provider_name;
            $data->provider_designation = $request->provider_designation;
            $data->provider_signature = $request->provider_signature;
            $data->reciever_name = $request->reciever_name;
            $data->reciever_designation = $request->reciever_designation;
            $data->reciever_signature = $request->reciever_signature;
            $data->security_officer = Auth::user()->name;
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
            $data=KeyReturnRegister::find($id);
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
