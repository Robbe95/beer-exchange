<?php

namespace App\Requests\Games;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomGameRequest extends FormRequest
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

            'description' => 'string',
            'min_players' => 'numeric',
            'max_players' => 'numeric',
            'min_play_time' => 'numeric',
            'max_play_time' => 'numeric',
            'min_age' => 'numeric',
            'year_published' => 'numeric',

            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ];
    }
}
