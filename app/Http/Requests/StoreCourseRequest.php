<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'instructor_id' => ['required', 'integer', 'exists:instructors,id'],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'level' => ['required', 'string', 'in:beginner,intermediate,advanced'],
            'duration_hours' => ['required', 'integer', 'min:0', 'max:500'],
            'is_published' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'instructor_id.required' => 'Instructor is required',
            'instructor_id.exists' => 'Selected instructor does not exist',
            'title.required' => 'Course title is required',
            'title.min' => 'Title must be at least 3 characters',
            'description.required' => 'Course description is required',
            'description.min' => 'Description must be at least 10 characters',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a valid number',
            'price.min' => 'Price cannot be negative',
            'level.required' => 'Course level is required',
            'level.in' => 'Level must be beginner, intermediate, or advanced',
            'duration_hours.required' => 'Duration is required',
            'duration_hours.integer' => 'Duration must be a valid number',
        ];
    }
}