<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|max:1000',
            'relevance'     => 'required|in:alta,media,baja',
            'approval_date' => 'nullable|date_format:d-m-Y H:i:s',
            'upload_date'   => 'nullable|date_format:d-m-Y H:i:s',
            'pdf_path'      => 'nullable|string|regex:/^\/[A-Za-z0-9_\-\/]+\.pdf$/',
        ];
    }
}
