<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class NotSameAsOldPassword implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $new_password;
    public function __construct($new_password)
    {
        $this->new_password = $new_password;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = auth()->user();
        return !Hash::check($this->new_password, $user->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The new password must not be the same as the old password.';
    }
}
