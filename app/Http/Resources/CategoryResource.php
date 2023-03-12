<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'active' => $this->active,
            'name' => $this->whenNotNull($this->name),
            'slug' => $this->whenNotNull($this->slug),
            'created_at_formated' => $this->when($this->created_at, function(){
                return $this->created_at->toDayDatetimeString();
            }),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
            'children_count' => $this->whenNotNull($this->children_count),
            'can' => [
                'edit' => $request->user()?->can('edit category'),
                'delete' => $request->user()?->can('delete category'),
            ]
        ];
    }
}
