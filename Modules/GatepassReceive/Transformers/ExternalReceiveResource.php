<?php

namespace Modules\GatepassReceive\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Location\Transformers\LocationResource;
use Modules\User\Transformers\UserResource;

class ExternalReceiveResource extends JsonResource
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
            'receive_date_time' => $this->receive_date_time,
            'receive_no' => $this->receive_no,
            'party' => $this->party,
            'gatepass_no' => $this->gatepass_no_from_party,
            'challan_no' => $this->challan_no,
            'to_person' => $this->to_person,
            'tag' => $this->tag,
            'note' => $this->note,
            'items' => $this->items ? ExternalReceiveItemResource::collection($this->items): null,
            'to_location' => $this->toLocationInfo 
                            ? new LocationResource($this->toLocationInfo)
                            :null,
            'received_by' => $this->receivedByUser 
                            ? new UserResource($this->receivedByUser)
                            :null,
            'to_department' => $this->toDepartmentInfo 
                            ? [
                                'id' => $this->toDepartmentInfo->id,
                                'name' => $this->toDepartmentInfo->name,
                            ]
                            :null,
        ];
    }
}
