<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RolesRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($this->route('role') ?? null)]
            /*
                $this-> hace referencia a la request actual
                route() hace referncia a la ruta actual
                'role' extrae lo que se encuentre en el aprámetro role de la request actual, que en este caso es el modelo
                con el id enviado, ya que se hizo route model binding
            */
        ];
    }
}
