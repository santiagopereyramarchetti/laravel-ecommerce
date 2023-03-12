<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class CategoriesRequest extends FormRequest
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

        $model = $this->route('category');

        return [
            'name' => ['bail', 'required', 'string', 'max:255'],
            'parentId' => ['bail', 'nullable', 'integer', Rule::exists(Category::class, 'id')->whereNull('parent_id')],
            'slug' => ['bail', 'required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($model->id ?? null)],
            'active' => ['bail', 'boolean']
        ];
    }
}
