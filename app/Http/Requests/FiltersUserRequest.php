<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class FiltersUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // verifica que los atributos sean validos, si no establece un valor predefinido
        $validator = Validator::make($this->all(), [
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|in:5,10,20',
            'sort_by' => 'required|in:name,email',
            'sort_order' => 'required|in:asc,desc',
        ]);

        $errors = $validator->errors()->toArray();

        if (@$errors["page"])
            $this->merge(['page' => 1]);

        if (@$errors["per_page"])
            $this->merge(["per_page" => 10]);

        if (@$errors["sort_by"])
            $this->merge(["sort_by" => "name"]);

        if (@$errors["sort_order"])
            $this->merge(["sort_order" => "asc"]);

        return $validator;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
