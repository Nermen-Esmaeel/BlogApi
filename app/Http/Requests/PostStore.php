<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class PostStore extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100' ,
            'content' => 'required|string|max:600' ,
            'slug' => 'required|string',
            'type'  => 'required|in:paid,free',
            'category_id' =>'required',
        ];
    }
    public function messages(){
        return [
             'title.required' => 'Title is required and must not be greater than 100 characters. ' ,
             'content.required' => 'Content is required and must not be greater than 600 characters. ' ,
             'slug.required' => 'slug must be string ' ,
             'type.in' => 'Subscription Plan  must be paid or free' ,

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' =>false,
            'errors' => $validator->errors()
        ]));

    }

}
