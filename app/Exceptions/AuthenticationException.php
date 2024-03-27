<?php

namespace App\Exceptions;

use Exception;

class AuthenticationException extends Exception
{
    public function __construct()
    {
        parent::__construct('Ваши данные неверны. Проверьте их и попробуйте снова', 401);
    }
}
