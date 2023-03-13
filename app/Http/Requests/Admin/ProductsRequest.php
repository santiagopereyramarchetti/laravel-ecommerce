<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class ProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {

        $model = $this->route('product');

        return [
            'name' => ['bail', 'required', 'string', 'max:255'],
            'slug' => ['bail', 'required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($model->id ?? null)],
            'description' => ['bail', 'required', 'string'],
            'costPrice' => ['bail', 'required', 'integer'],
            'price' => ['bail', 'required', 'integer'],
            'featured' => ['bail', 'boolean'],
            'showOnSlider' => ['bail', 'boolean'],
            'active' => ['bail', 'boolean'],
            'categoryId' => ['bail', 'required', Rule::exists(Category::class, 'id')->whereNull('parent_id')],
            'subCategoryId' => ['bail', 'nullable', Rule::exists(Category::class, 'id')->whereNotNull('parent_id')],
        ];
    }

    public function saveData(): array{
        $data = $this->safe()->only(['name','slug','description','price','featured','active']);
        $data['cost_price'] = $this->costPrice;
        $data['show_on_slider'] = $this->showOnSlider;

        return $data;
    }

    public function categoryIds(): Collection{
        return collect([$this->categoryId, $this->subCategoryId])->filter()->values();
    }
}
