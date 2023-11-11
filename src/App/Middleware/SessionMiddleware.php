<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class SessionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        session_save_path("/Applications/XAMPP/xamppfiles/temp/");
        session_start();
        dd($_SESSION);

        $next();
    }
}