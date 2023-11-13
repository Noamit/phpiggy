<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use App\Exceptions\SessionException;

class SessionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException("Session already active. ");
        }


        //headers_sent , without session
        // if this 2 lines is before the conditions, it is error and will catch the error :
        // ob_end_clean();
        // echo "hello";
        if (headers_sent($filename, $line)) {
            throw new SessionException(
            "Headers already sent. Consider enabling output buffering. Data outputted from {$filename} - Line: {$line}"
            );
        }
        
        // session_save_path("/Applications/XAMPP/xamppfiles/temp/");
        session_start();

        $next();
        session_write_close();
    }
}