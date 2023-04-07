<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
{
    public $validator = null;

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
    		return [];
    		case 'POST': {
    			return [
    				'producto_id'   => 'required',
                    'talla_id'      => 'required|gt:0',
                    'color_id'      => 'required|gt:0',
    				'cantidad'      => 'required',
    			];
    		}
    		case 'PUT': {
    			return [
    				'producto_id'   => 'required',
                    'talla_id'      => 'required',
                    'color_id'      => 'required',
    				'cantidad'      => 'required',
    			];
    		}
    	}
    }


    protected function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
}
