<?php

namespace App\Support\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- Obviously needed to validate rules

/**
 * @OA\Schema(
 *   schema="UserStoreRequest",
 *   description="Create new user",
 *   required={"nickname", "name", "email", "password"},
 *   @OA\Property(
 *      property="nickname",
 *      type="string",
 *      example="jane",
 *      description="User nickname",
 *      minLength=1,
 *      maxLength=29,
 *   ),
 *   @OA\Property(
 *      property="name",
 *      type="string",
 *      example="Jane Doe",
 *      description="User full name",
 *      minLength=1,
 *      maxLength=191,
 *   ),
 *   @OA\Property(
 *      property="email",
 *      type="string",
 *      minLength=1,
 *      maxLength=191,
 *      description="User email",
 *      example="JaneDoe@email.com",
 *   ),
 *   @OA\Property(
 *      property="password",
 *      type="string",
 *      minLength=1,
 *      maxLength=191,
 *      description="User Password",
 *      example="correct horse battery staple",
 *   ),
 * )
 * 
 * Get the validation rules that apply to the request.
 *
 * @return array
 */
class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nickname' => 'required|string|unique:users|max:29|min:1',    // (Must be shorter than 30 characters => max:29)
            'name'     => 'required|string|max:191|min:1',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|max:191',
        ];
    }
}
