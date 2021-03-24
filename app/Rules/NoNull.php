<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoNull implements Rule
{
    private $user;
    private $existe;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        $this->existe = false;
        if (!isset($this->user->memberof)) {
            return $this->existe;
        }
        foreach ($this->user->memberof as $key => $value) {

            if (strpos($value, 'Sistema_nomina') != false) {
                $this->existe = true;
            }
        }
        return $this->existe == true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El indicador no se encuentra en nuestro directorio';
    }
}
