<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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
    public function rules(Request $request)
    {
        return [
            'customer_name' => [
                'required',
                Rule::unique('customers')->where(function ($query) use ($request) {
                    return $query->where('customer_name', $request->customer_name)
                       ->where('contact', $request->contact)
                       ->where(company());
                 }),
            ],
            'contact' => [
                'required',
                Rule::unique('customers')->where(function ($query) use ($request) {
                    return $query->where('customer_name', $request->customer_name)
                       ->where('contact', $request->contact)
                       ->where(company());
                 }),
            ]
        ];
    }
    public function messages(){
        return [
            'required' => "The :attribute field is required",
            'unique' => 'already exists',
        ];
    }
}
