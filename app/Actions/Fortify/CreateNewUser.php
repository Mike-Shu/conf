<?php

namespace App\Actions\Fortify;

use App\Jobs\SendWelcomeEmailJob;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param array<string, string> $input
     *
     * @return User
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            $originalPassword = Str::random(8);

            return tap(User::create([
                'first_name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($originalPassword),
            ]), function (User $user) use ($originalPassword) {
//                $this->createTeam($user);

                if (!app()->environment(["testing"])) {
                    SendWelcomeEmailJob::dispatch([
                        'email' => $user->email,
                        'password' => $originalPassword,
                        'tenant_id' => tenant()->id,
                    ]);
                }
            });
        });
    }

    /**
     * TODO: deprecated?
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0] . "'s Team",
            'personal_team' => true,
        ]));
    }
}
