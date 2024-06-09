<?php

namespace Modules\Exportgoods7Point\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Exportgoods7Point\Entities\Exportgoods7Point;
use App\Traits\ApiResponseTrait;
class Exportgoods7PointController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ApiResponseTrait;
    public function index(Request $request)
    {
        try {

            $KeyControl=Exportgoods7Point::orderBy('id', 'desc')
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
        return view('exportgoods7point::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = new Exportgoods7Point();
            $data->out_date = $request->out_date;
            $data->out_time = $request->out_time;
            $data->in_time = $request->in_time;
            $data->loading_purpose = $request->loading_purpose;
            $data->driver_name = $request->driver_name;
            $data->driver_licence_no = $request->driver_licence_no;
            $data->bolt_seal_no = $request->bolt_seal_no;
            $data->vehicale_no = $request->vehicale_no;
            $data->vehicle_type = $request->vehicle_type;
            $data->loading_item_name = $request->loading_item_name;
            $data->suplier_company_name = $request->suplier_company_name;
            $data->destination_from = $request->destination_from;
            $data->clean_inside_cover_van = $request->clean_inside_cover_van;
            $data->points7_check = $request->points7_check;
            $data->transport_fitness = $request->transport_fitness;
            $data->checked_by_door = $request->checked_by_door;
            $data->bolt_locked_officer_name = $request->bolt_locked_officer_name;
            $data->shipment_officer_signature = $request->shipment_officer_signature;
            $data->sy_off = $request->sy_off;
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
        return view('exportgoods7point::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $data = Exportgoods7Point::find($id);
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
            $data =Exportgoods7Point::find($id);
            $data->out_date = $request->out_date;
            $data->out_time = $request->out_time;
            $data->in_time = $request->in_time;
            $data->loading_purpose = $request->loading_purpose;
            $data->driver_name = $request->driver_name;
            $data->driver_licence_no = $request->driver_licence_no;
            $data->bolt_seal_no = $request->bolt_seal_no;
            $data->vehicale_no = $request->vehicale_no;
            $data->vehicle_type = $request->vehicle_type;
            $data->loading_item_name = $request->loading_item_name;
            $data->suplier_company_name = $request->suplier_company_name;
            $data->destination_from = $request->destination_from;
            $data->clean_inside_cover_van = $request->clean_inside_cover_van;
            $data->points7_check = $request->points7_check;
            $data->transport_fitness = $request->transport_fitness;
            $data->checked_by_door = $request->checked_by_door;
            $data->bolt_locked_officer_name = $request->bolt_locked_officer_name;
            $data->shipment_officer_signature = $request->shipment_officer_signature;
            $data->sy_off = $request->sy_off;
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
            $data=Exportgoods7Point::find($id);
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
