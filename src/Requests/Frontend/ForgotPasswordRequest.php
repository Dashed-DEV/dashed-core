<?php

namespace Dashed\DashedCore\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email:rfc',
                'max:255',
            ],
        ];
    }
}
