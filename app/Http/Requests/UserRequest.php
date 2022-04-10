<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            case 'POST':
            return [];
            case 'PUT': {
             return [
                'usuario'        => 'required|min:1|max:150',
                'nombres'        => 'required|min:1|max:150',
                'apellidos'      => 'required|min:1|max:150',
                'direccion'      => 'required|min:1|max:150',
                'departamento'   => 'required',
                'municipio'      => 'required',
                'telefono'       => 'required|min:1|max:150',
                'imagen'         => 'sometimes|image|mimes:jpg,jpeg,png',
            ];
            }
        }
    }
}
