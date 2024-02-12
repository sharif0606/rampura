<?php

namespace App\Http\Requests\Accounts\ChildOne;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
        $id=encryptor('decrypt',$r->uptoken);
        return [
            'sub_head'=> 'required',
            'head_name'=> 'required',
            // 'head_code'=> 'required|unique:child_ones,head_code,'.$id,
            'head_code' => [
                'required',
                Rule::unique('child_ones')->where(function ($query) use ($r) {
                    return $query->where('head_code', $r->head_code)
                       ->where(company());
                 })->ignore($id),
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
