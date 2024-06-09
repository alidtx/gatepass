<?php

namespace Modules\WastageCarryingLabor\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\WastageCarryingLabor\Entities\WastageCarryingLabor;
use App\Traits\ApiResponseTrait;
class WastageCarryingLaborController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $KeyControl=WastageCarryingLabor::orderBy('id', 'desc')
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
        return view('wastagecarryinglabor::create');
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
            $data = new WastageCarryingLabor();
            $data->date = $request->date;
            $data->entry = $request->entry;
            $data->name = $request->name;
            $data->company = $request->company;
            $data->mobile_no = $request->mobile_no;
            $data->issue_card = $request->issue_card;
            $data->return_card = $request->return_card;
            $data->signature = $request->signature;
            $data->outter = $request->outter;
            $data->Siki_signature = $request->Siki_signature;
            $data->Siki_officer_signature = $request->Siki_officer_signature;
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
        return view('wastagecarryinglabor::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $data = WastageCarryingLabor::find($id);
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
            $data = WastageCarryingLabor::find($id);
            $data->date = $request->date;
            $data->entry = $request->entry;
            $data->name = $request->name;
            $data->company = $request->company;
            $data->mobile_no = $request->mobile_no;
            $data->issue_card = $request->issue_card;
            $data->return_card = $request->return_card;
            $data->signature = $request->signature;
            $data->outter = $request->outter;
            $data->Siki_signature = $request->Siki_signature;
            $data->Siki_officer_signature = $request->Siki_officer_signature;
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
            $data=WastageCarryingLabor::find($id);
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
