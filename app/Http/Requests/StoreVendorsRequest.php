<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorsRequest extends FormRequest
{
     /**
     * Determine if the user is authorized to make this request
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
        return [
            'name' => 'required|max:30',
            // 'last_name' => 'required|max:30',
            'email' => 'required|string|email|max:100|unique:vendors,email,'.$this->id,
            // 'currency_id' => 'required',
        ];
    }
}
