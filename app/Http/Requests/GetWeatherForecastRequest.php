<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetWeatherForecastRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'location' => $this->route('location'),
            'days'    => $this->query('days', 1),
            'units'    => $this->query('units', 'metric')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'location'  => 'required|string',
            'days'      => ['bail', 'sometimes', 'numeric', 'min:1', 'max:5'],
            'units'     => 'sometimes|in:metric,imperial',
        ];
    }
}
