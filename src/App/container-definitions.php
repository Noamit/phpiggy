<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
use App\Services\{ValidatorService, UserService, TransactionService, ReceiptService, CategoryService, ChartService};

return [
    // new TemplateEngine(Paths::VIEWS) is the return value of the function
    // fn () is arrow function
    TemplateEngine::class => fn () => new TemplateEngine(Paths::VIEWS),
    ValidatorService::class => fn () => new ValidatorService(),
    Database::class => fn () => new Database($_ENV['DB_DRIVER'], [
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'dbname' => $_ENV['DB_NAME'],
       ], $_ENV['DB_USER'], $_ENV['DB_PASS']),
    
    UserService::class => function (Container $container) {
        $db = $container->get(Database::class);
        return new UserService($db);
    },
    TransactionService::class => function (Container $container) {
        $db = $container->get(Database::class);
        return new TransactionService($db);
    },
    ReceiptService::class => function (Container $container) {
        $db = $container->get(Database::class);
        return new ReceiptService($db);
    },
    CategoryService::class => function (Container $container) {
        $db = $container->get(Database::class);
        return new CategoryService($db);
    },
    ChartService::class => function (Container $container) {
        $db = $container->get(Database::class);
        return new ChartService($db);
    }           
];