<?php

namespace Modules\RegisterFileInspection\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RegisterFileInspection\Entities\RegisterFileInspection;
use App\Traits\ApiResponseTrait;

class RegisterFileInspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $RegisterFileInspection=RegisterFileInspection::orderBy('id', 'desc')
            ->orderBy('id', 'desc')
            ->sortable();

            $result = $request->per_page 
            ? $RegisterFileInspection->paginate($request->per_page)
            : $RegisterFileInspection->paginate(10);
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
        return view('registerfileinspection::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = new RegisterFileInspection();
            $data->name = $request->name;
            $data->jan = $request->jan;
            $data->fab = $request->fab;
            $data->mar = $request->mar;
            $data->april = $request->april;
            $data->may = $request->may;
            $data->june = $request->june;
            $data->july = $request->july;
            $data->aug = $request->aug;
            $data->sep = $request->sep;
            $data->oct = $request->oct;
            $data->nov = $request->nov;
            $data->dec = $request->dec;
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
        return view('registerfileinspection::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request,$id)
    {
        try {
            $data = RegisterFileInspection::find($id);
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
            $data =RegisterFileInspection::find($id);
            $data->name = $request->name;
            $data->jan = $request->jan;
            $data->fab = $request->fab;
            $data->mar = $request->mar;
            $data->april = $request->april;
            $data->may = $request->may;
            $data->june = $request->june;
            $data->july = $request->july;
            $data->aug = $request->aug;
            $data->sep = $request->sep;
            $data->oct = $request->oct;
            $data->nov = $request->nov;
            $data->dec = $request->dec;
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
            $data=RegisterFileInspection::find($id);
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
