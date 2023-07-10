<?php

namespace App\Http\Requests;

class GetCurrentWeatherRequest extends BaseRequest
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
            'units'     => 'sometimes|in:metric,imperial',
        ];
    }
}
