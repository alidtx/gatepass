<?php

namespace Modules\Gatepass\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class GatepassItemResource extends JsonResource
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
            'qty' => $this->qty,
            'item_name' => $this->itemsInfo?->itemInfo?->name,
            'item_description' => $this->itemsInfo?->item_description,
            'item_unit' => $this->itemsInfo?->itemInfo?->unitInfo?->name,
        ];
    }
}
