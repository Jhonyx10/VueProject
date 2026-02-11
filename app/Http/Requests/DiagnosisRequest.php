<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_id'   => ['required', 'string'],
            'patient_id'       => ['required', 'string'],
            'type'             => ['required', 'in:primary,secondary'],
            'status'           => ['required', 'in:preliminary,final'],
            'condition_name'   => ['required', 'string', 'min:3', 'max:255'],
            'severity'         => ['required', 'in:mild,moderate,severe,acute'],
            'onset_date'       => ['nullable', 'date'],
            'notes'            => ['nullable', 'string', 'max:2000'],
            'is_resolved'      => ['nullable', 'boolean'],
            
            // Validating the Array of Clinical Codes
            'clinical_codes'          => ['nullable', 'array'],
            'clinical_codes.*.code'   => ['required_with:clinical_codes.*.system', 'nullable', 'string'],
            'clinical_codes.*.system' => ['required_with:clinical_codes.*.code', 'nullable', 'string'],

            // Validating the Vitals Embedded Object
            'vitals_at_time'      => ['nullable', 'array'],
            'vitals_at_time.bp'   => ['nullable', 'string', 'regex:/^\d{2,3}\/\d{2,3}$/'], // Validates 120/80 format
            'vitals_at_time.temp' => ['nullable', 'numeric', 'between:30,45'],
        ];
    }

    public function messages(): array
    {
        return [
            'vitals_at_time.bp.regex' => 'Blood pressure must be in format (systolic/diastolic, e.g., 120/80).',
            'condition_name.required' => 'Please provide a diagnosis name.',
            'clinical_codes.*.code.required_with' => 'The clinical code cannot be empty if a system is selected.',
            'clinical_codes.*.system.required_with' => 'The system must be selected if a code is provided.',
        ];
    }
}