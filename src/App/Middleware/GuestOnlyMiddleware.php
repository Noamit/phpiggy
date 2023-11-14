<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class GuestOnlyMiddleware implements MiddlewareInterface
{
  public function process(callable $next)
  {
    
    // it the user is logged in, redirect to home page
    if (!empty($_SESSION['user'])) {
        redirectTo("/");
    }
    $next();
  }
}