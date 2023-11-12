<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        try {
            $next();
        } catch (ValidationException $e) {
            // dd($e->errors);
            $oldFormData = $_POST;
            $excludedFields = ['password', 'confirmPassword'];
            //password, confirmPassword will exclude
            // array_flip flip the values to be keys
            $filteredFormData = array_diff_key($oldFormData, array_flip($excludedFields));
            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $filteredFormData;
            $referer = $_SERVER['HTTP_REFERER'];
            redirectTo($referer);
        }
    }
}