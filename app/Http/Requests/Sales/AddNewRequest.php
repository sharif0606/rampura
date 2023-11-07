<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class AddNewRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'warehouse_id'=>'required',
            'customerName'=>'required',
            // 'rate_in_kg'=>'required',
            'sales_date'=>'required'
        ];
    }
    public function messages(){
        return [
            'required' => "This filed is required",
        ];
    }
}
