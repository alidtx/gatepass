<?php

namespace Modules\ShortLeave\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ShortLeave\Entities\ShortLeave;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Modules\ShortLeave\Http\Requests\ShortLeaveRequest;
class ShortLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {   
        try {

            $ShortLeave=ShortLeave::orderBy('id', 'desc')
            ->orderBy('id', 'desc')
            ->sortable();

            $result = $request->per_page 
            ? $ShortLeave->paginate($request->per_page)
            : $ShortLeave->paginate(10);
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
        return view('shortleave::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = new ShortLeave();
        $data->date = $request->date;
        $data->name = $request->name;
        $data->ids = $request->ids;
        $data->designation = $request->designation;
        $data->department = $request->department;
        $data->from = $request->from;
        $data->to = $request->to;
        $data->comment = $request->comment;
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
        return view('shortleave::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request,$id)
    {
        try {
            $data = ShortLeave::find($id);
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
        $data =ShortLeave::find($id);
        $data->date = $request->date;
        $data->name = $request->name;
        $data->ids = $request->ids;
        $data->designation = $request->designation;
        $data->department = $request->department;
        $data->from = $request->from;
        $data->to = $request->to;
        $data->comment = $request->comment;
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
        $data=ShortLeave::find($id);
        if($data)  {
          $data->delete();
        }
        return $this->successResponse(200, 'Data deleted successfully');
    }
}
