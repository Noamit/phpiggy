<?php

declare(strict_types=1);

use Framework\TemplateEngine;
use App\Config\Paths;
use App\Services\ValidatorService;

return [
    // new TemplateEngine(Paths::VIEWS) is the return value of the function
    // fn () is arrow function
    TemplateEngine::class => fn () => new TemplateEngine(Paths::VIEWS),
    ValidatorService::class => fn () => new ValidatorService()
];