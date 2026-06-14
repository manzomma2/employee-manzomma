<?php

namespace App\Http\Requests;

use App\Models\Vacation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class CutVacationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cut_date' => 'required|date',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->has('cut_date')) {
                return;
            }

            $routeVacation = $this->route('vacation');
            $vacation = $routeVacation instanceof Vacation ? $routeVacation : Vacation::find($routeVacation);

            if (! $vacation) {
                return;
            }

            $cutDate = Carbon::parse($this->input('cut_date'));

            if ($vacation->status !== 'active') {
                $validator->errors()->add('cut_date', 'Only active vacations can be cut.');
            }

            if ($cutDate->lt($vacation->start_date)) {
                $validator->errors()->add('cut_date', 'The cut date must be after or equal to the vacation start date.');
            }

            if ($cutDate->gte($vacation->end_date)) {
                $validator->errors()->add('cut_date', 'The cut date must be before the vacation end date.');
            }
        });
    }
}
