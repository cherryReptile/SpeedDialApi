<?php

namespace App\Http\Requests;

use App\Models\Dial;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDialRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Dial::getRules();
    }
}
