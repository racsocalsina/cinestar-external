<?php

namespace App\Rules;

use App\Helpers\Helper;
use Illuminate\Contracts\Validation\Rule;

class Password implements Rule
{
    private $err_message;

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
        $validate = Helper::validatePassword($value);
        $this->err_message = $validate[1];
        return $validate[0];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->err_message;
    }
}
