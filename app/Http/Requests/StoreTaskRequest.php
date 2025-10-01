<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'section' => 'required|in:daily,weekly,monthly',
            'goal_id' => 'nullable|exists:goals,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a task title.',
            'title.max' => 'The task title cannot exceed 255 characters.',
            'section.required' => 'Please select a section for this task.',
            'section.in' => 'The section must be daily, weekly, or monthly.',
            'goal_id.exists' => 'The selected goal does not exist.',
            'due_date.after_or_equal' => 'The due date cannot be in the past.',
            'start_time.date_format' => 'The start time must be in HH:MM format.',
            'end_time.date_format' => 'The end time must be in HH:MM format.',
            'end_time.after' => 'The end time must be after the start time.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert empty strings to null for time fields
        if ($this->start_time === '') {
            $this->merge(['start_time' => null]);
        }
        if ($this->end_time === '') {
            $this->merge(['end_time' => null]);
        }
    }
}