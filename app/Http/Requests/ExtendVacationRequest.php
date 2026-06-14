<?php

namespace App\Http\Requests;

use App\Models\Vacation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class ExtendVacationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'extension_date' => 'required|date',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->has('extension_date')) {
                return;
            }

            $routeVacation = $this->route('vacation');
            $vacation = $routeVacation instanceof Vacation ? $routeVacation : Vacation::find($routeVacation);

            if (! $vacation) {
                return;
            }

            $extensionDate = Carbon::parse($this->input('extension_date'));

            if ($extensionDate->lte($vacation->end_date)) {
                $validator->errors()->add('extension_date', 'The extension date must be after the vacation end date.');
            }
        });
    }
}
