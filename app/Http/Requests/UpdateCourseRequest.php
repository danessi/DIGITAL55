<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'instructor_id' => ['sometimes', 'integer', 'exists:instructors,id'],
            'title' => ['sometimes', 'string', 'min:3', 'max:255'],
            'description' => ['sometimes', 'string', 'min:10'],
            'price' => ['sometimes', 'numeric', 'min:0', 'max:9999.99'],
            'level' => ['sometimes', 'string', 'in:beginner,intermediate,advanced'],
            'duration_hours' => ['sometimes', 'integer', 'min:0', 'max:500'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'instructor_id.exists' => 'Selected instructor does not exist',
            'title.min' => 'Title must be at least 3 characters',
            'description.min' => 'Description must be at least 10 characters',
            'price.numeric' => 'Price must be a valid number',
            'price.min' => 'Price cannot be negative',
            'level.in' => 'Level must be beginner, intermediate, or advanced',
            'duration_hours.integer' => 'Duration must be a valid number',
        ];
    }
}