<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class NoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('post')) {
            return [
                'note' => 'string|nullable',
                'rate' => 'integer|nullable|numeric|gte:0|lte:10',
                'state' => 'integer|required|numeric|between:0,5',
                'cover' => 'nullable|url|string|max:255',
                'permission' => 'required|integer|numeric|between:0,2',
                'title' => 'required|string',
            ];
        }
    }
}
