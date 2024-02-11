<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePokemonRequest extends FormRequest
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
            'name' => 'required|string|unique:pokemons,name',
            'image' => 'required|file|mimes:jpg,bmp,png',
            'shape' => 'required|in:head,head_legs,fins,wings',
            'location' => 'required|exists:locations,name|string|max:255',
            'ability_ru' => 'required|regex:/[А-Яа-яЁё]/u|string|max:255',
            'ability_eng' => 'required|regex:/[A-Za-z]/u|string|max:255',
            'ability_img' => 'required|file|mimes:jpg,bmp,png'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ],422));
    }
}
