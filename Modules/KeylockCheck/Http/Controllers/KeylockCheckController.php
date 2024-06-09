<?php

namespace Modules\KeylockCheck\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\KeylockCheck\Entities\KeylockCheck;
use App\Traits\ApiResponseTrait;
use Modules\KeylockCheck\Http\Requests\KeylockCheckRequest;

class KeylockCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {
            $KeylockCheck=KeylockCheck::orderBy('id', 'desc')
            ->orderBy('id', 'desc')
            ->sortable();

            $result = $request->per_page 
            ? $KeylockCheck->paginate($request->per_page)
            : $KeylockCheck->paginate(10);
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
        return view('keylockcheck::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(KeylockCheckRequest $request)
    {
        try {
            $data = new KeylockCheck();
            $data->date = $request->date;
            $data->num_of_key = $request->num_of_key;
            $data->okey = $request->okey;
            $data->broken = $request->broken;
            $data->missing = $request->missing;
            $data->additional = $request->additional;
            $data->action = $request->action;
            $data->authorized_by = $request->authorized_by;
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
        return view('keylockcheck::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(KeylockCheckRequest $request,$id)
    {
        try {
            $data = KeylockCheck::find($id);
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
            $data =KeylockCheck::find($id);
            $data->date = $request->date;
            $data->num_of_key = $request->num_of_key;
            $data->okey = $request->okey;
            $data->broken = $request->broken;
            $data->missing = $request->missing;
            $data->additional = $request->additional;
            $data->action = $request->action;
            $data->authorized_by = $request->authorized_by;
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
            $data=KeylockCheck::find($id);
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
