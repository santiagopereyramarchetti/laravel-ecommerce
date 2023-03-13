<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'cost_price' => $this->cost_price,
            'price' => $this->price,
            'featured' => $this->featured,
            'show_on_slider' => $this->show_on_slider,
            'active' => $this->active,
            'created_at_formated' => $this->when($this->created_at, function(){
                return $this->created_at->toDayDatetimeString();
            }),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'category_id' => $this->whenLoaded('categories', fn () => $this->categories->firstWhere('parent_id', null)?->id),
            'sub_category_id' => $this->whenLoaded('categories', fn () => $this->categories->firstWhere('parent_id', '!=', null)?->id),
            'can' => [
                'edit' => $request->user()?->can('edit product'),
                'delete' => $request->user()?->can('delete product'),
            ]
        ];
    }
}
