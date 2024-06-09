<?php

namespace Modules\WastageDelivery\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\WastageDelivery\Entities\WastageDelivery;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Modules\WastageDelivery\Http\Requests\WastageDeliveryRequest;
use Carbon\Carbon;
class WastageDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {

        try {

            $wastageDelivery=WastageDelivery::orderBy('id', 'desc')
            ->orderBy('id', 'desc')
            ->sortable();

            $result = $request->per_page 
            ? $wastageDelivery->paginate($request->per_page)
            : $wastageDelivery->paginate(10);
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
        return view('wastagedelivery::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        
        $data = new WastageDelivery();
            $data->date = $request->date;
            $data->name =  Auth::user()->name;
            $data->from = $request->from;
            $data->till = $request->till;
            $data->jhoot = $request->jhoot;
            $data->carton = $request->carton;
            $data->poly = $request->poly;
            $data->comment = $request->comment;
            $data->chot = $request->chot;
            $data->chot_kg = $request->chot_kg;
            $data->jhoot_kg = $request->jhoot_kg;
            $data->pipe = $request->pipe;
            $data->pipe_kg = $request->pipe_kg;
            $data->carton_kg = $request->carton_kg;
            $data->poly_kg = $request->poly_kg;
            $data->created_by = Auth::user()->id;
            $data->save();
            return $this->successResponse(200, 'Data Stored Successfully', $data);
    }

 
    public function show($id)
    {
        return view('wastagedelivery::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request,$id)
    {  
        try {
            $data = WastageDelivery::find($id);
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
        
        $data =WastageDelivery::find($id);
        $data->date = $request->date;
        $data->name =  Auth::user()->name;
        $data->from = $request->from;
        $data->till = $request->till;
        $data->jhoot = $request->jhoot;
        $data->carton = $request->carton;
        $data->poly = $request->poly;
        $data->comment = $request->comment;
        $data->chot = $request->chot;
        $data->chot_kg = $request->chot_kg;
        $data->jhoot_kg = $request->jhoot_kg;
        $data->pipe = $request->pipe;
        $data->pipe_kg = $request->pipe_kg;
        $data->carton_kg = $request->carton_kg;
        $data->poly_kg = $request->poly_kg;
        $data->created_by = Auth::user()->id;
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
        $data=WastageDelivery::find($id);
          if($data)  {
            $data->delete();
          }
          return $this->successResponse(200, 'Data deleted successfully');
    }
}
