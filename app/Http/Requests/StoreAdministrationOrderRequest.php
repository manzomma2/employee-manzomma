<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdministrationOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'sector_id' => 'required|exists:sectors,id',
            'department_id' => 'required|exists:departments,id',
            'work_job' => 'required|string|max:255',
            'order_date' => 'required|date',
            'inform_date' => 'nullable|date',
            'transfer_date' => 'nullable|date',
            'combine_date' => 'nullable|date',
            'active' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'The employee is required.',
            'employee_id.exists' => 'The selected employee is invalid.',
            'sector_id.required' => 'The sector is required.',
            'sector_id.exists' => 'The selected sector is invalid.',
            'department_id.required' => 'The department is required.',
            'department_id.exists' => 'The selected department is invalid.',
            'work_job.required' => 'The work job is required.',
            'order_date.required' => 'The order date is required.',
        ];
    }
}
