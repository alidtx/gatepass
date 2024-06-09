<?php

namespace Modules\GatepassReceive\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Gatepass\Transformers\GatepassItemResource;
use Modules\Location\Transformers\LocationResource;
use Modules\User\Transformers\UserResource;

class InternalReceiveResource extends JsonResource
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
            'to_location' => $this->toLocationInfo ? new LocationResource($this->toLocationInfo) : null,
            'internal_receive_items' => $this->receivedItems ? InternalReceiveItemResource::collection($this->receivedItems): null,
            'gatepass_check_info' => [
                'id' => $this->gatepassCheckInfo?->id,
                'released_by_user' => $this->releasedUser 
                                        ? new UserResource($this->releasedUser)
                                        : null,
                'release_date_time' => $this->gatepassCheckInfo?->release_date_time,
                'gatepass_info' => [
                    'id' => $this->gatepassCheckInfo?->gatepassInfo?->id,
                    'gate_pass_no' => $this->gatepassCheckInfo?->gatepassInfo?->gate_pass_no,
                    'challan_no' => $this->gatepassCheckInfo?->gatepassInfo?->challan_no ?? null,
                    'created_by_user' => $this->createdByUser 
                                            ? new UserResource($this->createdByUser) 
                                            : null,
                    'creation_datetime' => $this->gatepassCheckInfo?->gatepassInfo?->creation_datetime,
                    'carrying_person' => $this->gatepassCheckInfo?->gatepassInfo?->carrying_person ?? null,
                    'type' => [
                        'id' => $this->gatepassCheckInfo?->gatepassInfo?->typeInfo->id,
                        'name' => $this->gatepassCheckInfo?->gatepassInfo?->typeInfo->name
                    ],
                    'party' => [
                        'id' => $this->gatepassCheckInfo?->gatepassInfo?->party?->id,
                        'name' => $this->gatepassCheckInfo?->gatepassInfo?->party?->name
                    ],
                    'gatepass_items' => $this->gatepassCheckInfo?->gatepassInfo?->items ? GatepassItemResource::collection($this->gatepassCheckInfo->gatepassInfo->items): null,
                    'to_person' => $this->gatepassCheckInfo?->gatepassInfo?->toPersonInfo 
                                    ? new UserResource($this->gatepassCheckInfo->gatepassInfo->toPersonInfo)
                                    : null,
                    'from_location' => $this->gatepassCheckInfo?->gatepassInfo?->fromLocationInfo 
                                        ? new LocationResource($this->gatepassCheckInfo->gatepassInfo->fromLocationInfo) 
                                        : null,
                ],
            ],
            'received_by_user' => $this->receivedByUser 
                                    ? new UserResource($this->receivedByUser) 
                                    : null,
        ];
    }
}
