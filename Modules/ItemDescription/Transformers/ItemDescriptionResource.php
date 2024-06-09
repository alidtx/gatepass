<?php

namespace Modules\ItemDescription\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemDescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'name' => $this->name,
            'status' => $this->status,
            'status_text' => $this->status==1 ? 'Active':'Inactive',
        ];
    }
}
