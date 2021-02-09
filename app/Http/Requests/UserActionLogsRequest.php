<?php

namespace App\Http\Requests;

use App\Rules\IsValidUser;
use App\Rules\ValidIsbn;
use Illuminate\Foundation\Http\FormRequest;

class UserActionLogsRequest extends FormRequest
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
            'action' => ['required','in:CHECKIN,CHECKOUT'],
            'isbn' => ['required', new ValidIsbn()],
            'user_id' => ['required', new IsValidUser()]
        ];
    }
}
