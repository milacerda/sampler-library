<?php

namespace App\Http\Requests;

use App\Rules\ValidIsbn;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'title' => ['required','string'],
            'isbn' => ['required','string', new ValidIsbn()],
            'publication_date' => ['required','date','date_format:Y-m-d','before:today'],
            'status' => ['required','in:AVAILABLE,CHECKED_OUT']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'publication_date.before' => "Publication date can't be in the future",
            'status.in' => "Invalid status"
        ];
    }
}
