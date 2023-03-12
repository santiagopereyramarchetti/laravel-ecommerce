<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UsersRequest extends FormRequest
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

        $model = $this->route('user');
        $passwordRule = $model ? ['nullable'] : ['required'];

        return [
            'name' => ['bail', 'required', 'string', 'max:255'],
            /*
                $this-> hace referencia a la request actual
                route() hace referncia a la ruta actual
                'role' extrae lo que se encuentre en el parámetro role de la request actual, que en este caso es el modelo
                con el id enviado, ya que se hizo route model binding
            */
            'email' => ['bail', 'required', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user') ?? null)],
            'password' => ['bail', ...$passwordRule, Password::default() ],
            'passwordConfirmation' => ['bail', 'required','same:password'],
            'roleId' => ['bail', 'required', Rule::exists(Role::class, 'id')]
        ];
    }
}
