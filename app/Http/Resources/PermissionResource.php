<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at_formated' => $this->when($this->created_at, function(){
                return $this->created_at->toDayDatetimeString();
            }),
            'can' => [
                'edit' => $request->user()?->can('edit permission'),
                'delete' => $request->user()?->can('delete permission'),
            ]
        ];
    }
}
