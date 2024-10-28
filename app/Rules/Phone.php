<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Phone implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        // dd($attribute,$value);
        for ($i = 0; $i < strlen($value); $i++) {
            $count = 1;
            for ($j = $i + 1; $j < strlen($value) ; $j++) {
                if ($value[$i] == $value[$j] && $value[$i] != ' ') {
                    $count++;
                    $value[$j] = '0';
                }
            }
            if ($count > 9) {
                return false;
            }else {
                return true;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid Phone Number.';
    }
}
