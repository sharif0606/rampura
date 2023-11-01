<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'supplierName'=> 'required',
            'supplierName' => [
                'required',
                Rule::unique('suppliers', 'supplier_name')->where('supplier_name', $this->input('supplierName')),
            ],
            'contact'=> 'required',
            'contact' => [
                'required',
                Rule::unique('suppliers', 'contact')->where('supplier_name', $this->input('supplierName')),
            ],
            'company_id' => [
                'required',
                Rule::unique('suppliers', 'company_id')->where('supplier_name', company()),
            ],
        ];
    }
    public function messages(){
        return [
            'required' => "The :attribute field is required",
            'unique' => 'already exists',
        ];
    }
}
