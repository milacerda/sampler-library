<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidIsbn implements Rule
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
        if (strlen($value) !== 10) {
            return false;
        }
    
        $total = 0;
        for($i = 0; $i < 10; $i++) {
            if ($value[$i] == "X") {
                $val = 10;
            } else {
                $val = $value[$i];
            }

            $total += $val * (10 - $i);
        }
    
        if ($total % 11 !== 0) {
            return false;
        }
      
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid ISBN.';
    }
}
