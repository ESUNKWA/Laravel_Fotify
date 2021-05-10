<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'prenoms' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ], 
        [
            'name.required' => 'Le nom est obligatoire',
            'prenoms.required' => 'Le prénoms est obligatoire',
            'email.required' => 'Le email est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire'
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'prenoms' => $input['prenoms'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
