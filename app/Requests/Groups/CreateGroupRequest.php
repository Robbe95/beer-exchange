<?php

namespace App\Requests\Groups;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends FormRequest
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
    public function rules()
    {
        return [
            'title' => 'required|string',
            'information' => 'string',
            'private_information' => 'string',
            'start_time' => 'numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ];
    }
}
