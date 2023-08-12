<?php

namespace App\Http\Requests\Accounts\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
    public function rules()
    {
        $id=encryptor('decrypt',$r->uptoken);
        return [
            'head_name'=> 'required',
            'head_code'=> 'required|unique:master_accounts,head_code,'.$id
        ];
    }
    public function messages(){
        return [
            'required' => "The :attribute filed is required"
        ];
    }
}
