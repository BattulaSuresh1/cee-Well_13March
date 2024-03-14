<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Products;

class StoreProductsRequest extends FormRequest
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
  
    //     public function rules()
    //     {
    //         return [
    //             //
    //             'item_code' => 'required|unique:Products|max:25'.$this->id,
    //             // 'item_code' => 'required|item_code|max:100|unique:products,item_code,'.$this->id,
    //         ];
    
    // }

    public function rules()
{
    $id = $this->route('id'); // Assuming you're using Laravel's route model binding

    return [
        'item_code' => 'required|max:25|unique:products,item_code,' . $id,
    ];
}

}