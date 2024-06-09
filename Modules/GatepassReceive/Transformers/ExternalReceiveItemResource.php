<?php

namespace Modules\GatepassReceive\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Item\Transformers\ItemResource;

class ExternalReceiveItemResource extends JsonResource
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
            'document_qty' => $this->document_qty,
            'received_qty' => $this->received_qty,
            'item_description' => $this->item_description,
            'item_details' => new ItemResource($this->itemInfo),
        ];
    }
}
