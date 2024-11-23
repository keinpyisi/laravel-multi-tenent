<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Client_Validation extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            "client_name" => ["required", "string"],
            "kana" => ["required", "string"],
            "account_name" => ["required", "string"],
            'logo' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'homepage' => ['nullable', 'string', 'url'],
            'e_mail' => ['nullable', 'string', 'email'],
            'login_id' => ["required", "string"],
            "user_name" => ["required", "string"],
            'password' => ['required', 'string', 'confirmed'],
        ];
    }

    /**
     * Get the custom error messages for the validator.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'client_name.required' => __('validation.required', ['attribute' => 'client_name']),
            'client_name.string' => __('validation.string', ['attribute' => 'client_name']),
            'kana.required' => __('validation.required', ['attribute' => 'kana']),
            'kana.string' => __('validation.string', ['attribute' => 'kana']),
            'account_name.required' => __('validation.required', ['attribute' => 'account_name']),
            'account_name.string' => __('validation.string', ['attribute' => 'account_name']),
            'logo.required' => __('validation.required', ['attribute' => 'logo']),
            'logo.file' => __('validation.file', ['attribute' => 'logo']),
            'logo.image' => __('validation.image', ['attribute' => 'logo']),
            'logo.mimes' => __('validation.mimes', ['attribute' => 'logo']),
            'logo.max' => __('validation.max', ['attribute' => 'logo']),
            'homepage.url' => __('validation.url', ['attribute' => 'homepage']),
            'e_mail.email' => __('validation.email', ['attribute' => 'e_mail']),
            'login_id.required' => __('validation.required', ['attribute' => 'login_id']),
            'login_id.string' => __('validation.string', ['attribute' => 'login_id']),
            'user_name.required' => __('validation.required', ['attribute' => 'user_name']),
            'user_name.string' => __('validation.string', ['attribute' => 'user_name']),
            'password.required' => __('validation.required', ['attribute' => 'password']),
            'password.string' => __('validation.string', ['attribute' => 'password']),
            'password.confirmed' => __('validation.confirmed', ['attribute' => 'password']),
        ];
    }
}
