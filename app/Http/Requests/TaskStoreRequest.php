<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer'],
            'priority' => ['required', 'in:low,medium,high'],
            'status' => ['required', 'in:backlog,todo,in_progress,done'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'board_id' => ['required', 'integer', 'exists:boards,id'],
        ];
    }
}
