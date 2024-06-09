<?php

namespace Modules\ItemDescription\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Traits\ApiResponseTrait;
use Modules\ItemDescription\Entities\ItemDescription;
use Modules\ItemDescription\Http\Requests\ItemDescriptionRequest;

class ItemDescriptionController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        // $this->middleware('permission:item-view-permission', ['only' => ['index','show']]);
        // $this->middleware('permission:item-create-permission', ['only' => ['create', 'store']]);
        // $this->middleware('permission:item-edit-permission', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:item-delete-permission', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $per_page = request()->per_page ? request()->per_page : 10;
            $itemDescriptions = ItemDescription::with('itemInfo','createdByUser')
                            ->filter(request()->all())
                            ->active()
                            ->orderBy('id', 'desc')
                            ->sortable()
                            ->paginate($per_page);
            
            return $this->successResponse(200, 'All Item Descriptions retrieved Successfully', $itemDescriptions);
        } catch (\Exception $ex) {
            \Log::error('Item Description List Exception: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('itemdescription::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ItemDescriptionRequest $request)
    {
        try {
            $itemDescription = new ItemDescription();
            $itemDescription->item_id = $request->item_id;
            $itemDescription->name = $request->name;
            $itemDescription->status = $request->status;
            $itemDescription->created_by = Auth::user()->id;
            $itemDescription->save();

            return $this->successResponse(201, 'Item Description Created Successfully', $itemDescription);
        } catch (\Exception $ex) {
            \Log::error('Item Description Store Exception: '. $ex->getMessage());
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
        try {
            $itemDescription = ItemDescription::with('itemInfo','createdByUser')->find($id);
            return $this->successResponse(200, 'Item Description Retrieved Successfully', $itemDescription);
        } catch (\Exception $ex) {
            \Log::error('Item Description Show Exception: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try{
            $itemDescription = ItemDescription::with('itemInfo','createdByUser')->find($id);
            return $this->successResponse(200, 'Item Description Retrieved Successfully', $itemDescription);
        } catch (\Exception $ex) {
            \Log::error('Item Description Edit Exception: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ItemDescriptionRequest $request, $id)
    {
        try {
            $itemDescription = ItemDescription::find($id);
            $itemDescription->item_id = $request->item_id;
            $itemDescription->name = $request->name;
            $itemDescription->status = $request->status;
            $itemDescription->save();

            return $this->successResponse(200, 'Item Description Updated Successfully', $itemDescription);
        } catch (\Exception $ex) {
            \Log::error('Item Description Update Exception: '. $ex->getMessage());
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
            $itemDescription = ItemDescription::find($id);
            $itemDescription?->delete();
            return $this->successResponse(200, 'Item Description Deleted Successfully');
        } catch (\Exception $ex) {
            \Log::error('Item Description Delete Exception: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}
