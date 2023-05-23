<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class EmailMustHaveTLD implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $message;

    public function __construct()
    {
        //
        $this->message = 'The email field is invalid.';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        if ($value === '') {
            $this->message = 'The email field is required.';
        }
        if (!strpos($value, '@')) {
            return false;
        }

        $domain = explode('@', $value)[1];

        return !!strpos($domain, '.');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
