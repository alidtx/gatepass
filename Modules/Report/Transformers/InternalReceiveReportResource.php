<?php

namespace Modules\Report\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Location\Transformers\LocationResource;
use Modules\GatepassReceive\Transformers\InternalReceiveItemResource;

class InternalReceiveReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $statuses = ['0'=> 'Full Received', '1' => 'Short Received', '2' => 'Excess Received'];
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'received_date_time' => $this->received_date_time,
            'tag' => $this->tag,
            'status' => $this->status,
            'status_text' => $statuses[$this->status],
            'from_location' => $this->gatepassCheckInfo?->fromLocationInfo 
                                ? new LocationResource($this->gatepassCheckInfo?->fromLocationInfo) 
                                : null,
            'to_location' => $this->toLocationInfo 
                                ? new LocationResource($this->toLocationInfo) 
                                : null,
            'gatepass_check_info' => [
                'id' => $this->gatepassCheckInfo?->id,
                'from_location_id' => $this->gatepassCheckInfo?->from_location_id,
                'release_date_time' => $this->gatepassCheckInfo?->release_date_time,
                'gatepass_info' => [
                    'id' => $this->gatepassCheckInfo?->gatepassInfo?->id,
                    'gate_pass_no' => $this->gatepassCheckInfo?->gatepassInfo?->gate_pass_no,
                    'creation_datetime' => $this->gatepassCheckInfo?->gatepassInfo?->creation_datetime,
                ]
            ],
            'received_items' => $this->receivedItems 
                                ? InternalReceiveItemResource::collection($this->receivedItems)
                                : null
        ];
    }
}
