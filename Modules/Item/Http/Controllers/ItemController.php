<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Traits\ApiResponseTrait;
use Modules\Item\Entities\Item;
use Modules\Item\Transformers\ItemResource;
use Modules\Item\Http\Requests\ItemRequest;

class ItemController extends Controller
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
    public function index(Request $request)
    {
        try {
            $items = Item::with('itemDescription', 'unitInfo','createdByUser')
                        ->filter($request->all())
                        ->orderBy('name', 'desc')
                        ->sortable();

            $items = $request->per_page 
                        ? $items->paginate($request->per_page)
                        : $items->paginate(10);
                        
            ItemResource::collection($items);
            return $this->successResponse(200, 'All Items retrieved Successfully', $items);
        } catch (\Exception $ex) {
            \Log::error('Item List Exception: '. $ex->getMessage());
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('gatepass::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ItemRequest $request)
    {
        try {
            $data = new Item();
            $data->name = $request->name;
            $data->unit_id = $request->unit_id;
            $data->status = $request->status;
            $data->created_by = Auth::user()->id;
            $data->save();
            
            return $this->successResponse(200, 'Data Stored Successfully.', $data);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
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
            $data = Item::with('unitInfo','createdByUser')->find($id);

            return $this->successResponse(200, 'Data Retrieved Successfully', new ItemResource($data));
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $data = Item::with('unitInfo','createdByUser')->find($id);
            
            return $this->successResponse(200, 'Data Retrieved Successfully', new ItemResource($data));
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
    public function update(ItemRequest $request, $id)
    {
        try {
            $data = Item::find($id);
            $data->name = $request->name;
            $data->unit_id = $request->unit_id;
            $data->status = $request->status;
            $data->save();

            return $this->successResponse(200, 'Data updated successfully', $data);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
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
            $data = Item::find($id);
            if($data) {
                $data->delete();
            }

            return $this->successResponse(200, 'Data deleted successfully');
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}
