<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLocationRequest extends FormRequest
{
    public function rules(): array
    {
        $countryIso2 = strtoupper((string) $this->input('country'));
        $stateId     = (int) $this->input('state_id');

        return [
            'country'  => ['required','string','size:2', Rule::exists('countries','iso2')],
            'state_id' => ['required','integer', Rule::exists('states','id')->where('country_code', $countryIso2)],
            'city_id'  => ['required','integer', Rule::exists('cities','id')->where('state_id', $stateId)],
        ];
    }

    public function messages(): array
    {
        return [
            'country.exists'  => 'Invalid country.',
            'state_id.exists' => 'Invalid state for selected country.',
            'city_id.exists'  => 'Invalid city for selected state.',
        ];
    }
}
