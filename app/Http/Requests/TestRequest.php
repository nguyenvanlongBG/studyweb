<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
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
            'type'=> 'required|min:0',
            'name'=>'required',
            'belong_id'=>'nullable|numeric|min:1',
            'reward_init'=>'required|numeric|min:0|max:2500',
            'allow_rework'=>'boolean',
            'mark_option'=>'numeric|min:0|max:1',
            'time_start'=>'required|date_format:Y-m-d H:i:s',
            'time_finish'=>'nullable|date_format:Y-m-d H:i:s'
        ];
    }
}
