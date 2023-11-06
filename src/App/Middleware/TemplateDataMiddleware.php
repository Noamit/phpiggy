<?php

declare(strict_types=1);

use Framework\Contracts\MiddlewareInterface;

class TemplateDataMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
    }
}
