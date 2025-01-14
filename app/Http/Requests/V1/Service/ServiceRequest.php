<?php

namespace App\Http\Requests\V1\Service;

use App\Http\Requests\Request;

/**
 * Class ServiceRequest
 * @package App\Http\Requests
 */
class ServiceRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'price' => ['required', 'numeric'],
        ];
    }
}
