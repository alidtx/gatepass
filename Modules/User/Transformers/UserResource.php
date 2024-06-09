<?php

namespace Modules\User\Transformers;
use Modules\Departments\Transformers\DepartmentResource;
use Modules\User\Transformers\UserSourceResource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'phone' => $this->phone,
            'email' => $this->email,
            'user_type' => $this->user_type,
            'department' => $this->department ? new DepartmentResource($this->department):null,
            'source' => $this->userSource ? new UserSourceResource($this->userSource):null
        ];
    }
}
