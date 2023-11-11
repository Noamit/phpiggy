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
            $_SESSION['errors'] = $e->errors;
            $referer = $_SERVER['HTTP_REFERER'];
            redirectTo($referer);
        }
    }
}