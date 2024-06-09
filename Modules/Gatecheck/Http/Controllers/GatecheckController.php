<?php

namespace Modules\Gatecheck\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Gatecheck\Http\Requests\GatecheckRequest;
use Modules\Gatecheck\Entities\Gatecheck;
use Modules\Gatepass\Entities\Gatepass;
use DB;

class GatecheckController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try{
            $gatecheck = Gatecheck::with('fromLocationInfo','gatepassInfo', 'releasedUser', 'createdByUser')
                            ->orderBy('id', 'desc')
                            ->sortable();

            $result = $request->per_page 
                        ? $gatecheck->paginate($request->per_page)
                        : $gatecheck->paginate(10);

            return $this->successResponse(200, 'All Gate Check retrieved Successfully', $result);

        } catch(\Exception $ex){
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
        return view('gatecheck::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(GatecheckRequest $request)
    {
        if (!Auth::user()->can('gatepass-create-permission')) {
            return $this->invalidResponse(403, ['Access denied!']);
        }else{
            DB::beginTransaction();
            try {
                $gateCheck = new Gatecheck();
                $gateCheck->from_location_id = $request->from_location_id;
                $gateCheck->released_by = $request->released_by;
                $gateCheck->release_date_time = $request->release_date_time;
                $gateCheck->gatepass_id = $request->gatepass_id;
                $gateCheck->status = $request->status;
                $gateCheck->created_by = $request->created_by;
                $gateCheck->save();

                DB::commit();
                return $this->successResponse(200, 'Gate Check stored successfully', $gateCheck);
            
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('gatecheck::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    
     public function edit($id)
     {
         if (!Auth::user()->can('gatepass-view-permission')) {
             return $this->invalidResponse(403, ['Access denied!']);
         }else{
             try {
                 $gatecheck = Gatecheck::with('gatepassInfo')->where('id', $id)->first();
                 return $this->successResponse(200, 'Gate Check retrieved Successfully', $gatecheck);
             } catch (\Exception $ex) {
                 \Log::error('Type List Exception: '. $ex->getMessage());
                 throw new \Exception($ex->getMessage());
             }
         }
         // return view('gatecheck::edit');
 
     }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(GatecheckRequest $request, $id)
    {
        // if (!Auth::user()->can('gatepass-edit-permission')) {
        //     return $this->invalidResponse(403, ['Access denied!']);
        // }else{
            DB::beginTransaction();
            try {
                $gateCheck = Gatecheck::find($id);
                $gateCheck->from_location_id = $request->from_location_id;
                $gateCheck->released_by = $request->released_by;
                $gateCheck->release_date_time = $request->release_date_time;
                $gateCheck->gatepass_id = $request->gatepass_id;
                $gateCheck->status = $request->status;
                $gateCheck->created_by = $request->created_by;
                $gateCheck->save();

                DB::commit();
                return $this->successResponse(200, 'Gate Check Updated successfully', $gateCheck);
            
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error($e->getMessage());
                throw new \Exception($e->getMessage());
            }
        // }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        // if (!Auth::user()->can('gatepass-delete-permission')) {
        //     return $this->invalidResponse(403, ['Access denied!']);
        // }else{
            try {
                $gatecheck = Gatecheck::with('gatepassInfo')->where('id', $id)->delete();
                return $this->successResponse(200, 'Gate Check retrieved Successfully', $gatecheck);
            } catch (\Exception $ex) {
                \Log::error('Type List Exception: '. $ex->getMessage());
                throw new \Exception($ex->getMessage());
            }
        // }
    }
}
