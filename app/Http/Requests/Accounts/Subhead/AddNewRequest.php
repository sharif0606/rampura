<?php

namespace App\Http\Requests\Accounts\Subhead;

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
    public function rules(Request $r)
    {
        return [
            'master_head'=> 'required',
            'head_name'=> 'required',
            // 'head_code'=> 'required|unique:sub_heads,head_code'
            'head_code' => [
                'required',
                Rule::unique('sub_heads')->where(function ($query) use ($r) {
                    return $query->where('head_code', $r->head_code)
                       ->where(company());
                 }),
            ]
        ];
    }
    public function messages(){
        return [
            'required' => "The :attribute filed is required",
            'unique' => "This :attribute is already used. Please try another",
        ];
    }
}
