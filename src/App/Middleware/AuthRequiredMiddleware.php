<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class AuthRequiredMiddleware implements MiddlewareInterface
{
  public function process(callable $next)
  {

    // it the user isnt logged in, redirect to login page
    if (empty($_SESSION['user'])) {
        redirectTo("/login");
    }
    $next();
  }
}