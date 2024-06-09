<?php

namespace Modules\Item\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\UserResource;
use Modules\ItemDescription\Transformers\ItemDescriptionResource;

class ItemResource extends JsonResource
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
            'name' => $this->name,
            'status' => $this->status,
            'status_text' => $this->status==1 ? 'Active':'Inactive',
            'unit_info' => [
                'id' => $this->unitInfo?->id,
                'name' => $this->unitInfo?->name,
            ],
            'created_by_user' => $this->createdByUser ? new UserResource($this->createdByUser):null
        ];
    }
}
