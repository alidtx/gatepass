<?php

namespace Modules\GatepassReceive\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Gatepass\Transformers\GatepassItemResource;
use Modules\ItemDescription\Transformers\ItemDescriptionResource;

class InternalReceiveItemResource extends JsonResource
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
            'received_qty' => $this->received_qty,
            'item_info' => [
                'id' => $this->itemsInfo?->id,
                'gatepass_id' => $this->itemsInfo?->gatepass_id,
                'item_id' => $this->itemsInfo?->item_id,
                'item_description' => $this->itemsInfo?->item_description,
                'unit_id' => $this->itemsInfo?->unit_id,
                'qty' => $this->itemsInfo?->qty,
                'item_name' => $this->itemsInfo?->itemInfo?->name,
                'item_unit' => $this->itemsInfo?->itemInfo?->unitInfo?->name
            ],
            'unit_info' => $this->itemsInfo?->unitInfo ? [
                'id' => $this->itemsInfo?->unitInfo?->id,
                'name' => $this->itemsInfo?->unitInfo?->name,
            ]: null,
            'difference' => $this->itemsInfo?->qty - $this->received_qty 
        ];
    }
}
