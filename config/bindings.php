<?php

declare(strict_types=1);

use App\Infrastructure\Contracts\ConductorRepositoryInterface;
use App\Infrastructure\Contracts\PropietarioRepositoryInterface;
use App\Infrastructure\Contracts\TaxiRepositoryInterface;
use App\Infrastructure\Contracts\UserRepositoryInterface;
use App\Infrastructure\Contracts\AuditLogRepositoryInterface;
use App\Application\Contracts\AuthServiceInterface;
use Core\Container;
use App\Presentation\Http\Request;
use App\Infrastructure\Persistence\Repositories\ConductorRepository;
use App\Infrastructure\Persistence\Repositories\PropietarioRepository;
use App\Infrastructure\Persistence\Repositories\TaxiRepository;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Infrastructure\Persistence\Repositories\AuditLogRepository;
use App\Application\Contracts\AuditServiceInterface;
use App\Application\Services\AuditService;
use App\Application\Services\AuthService;
use App\Application\Services\PerfilService;
use App\Application\Services\ReporteService;
use App\Application\Services\TurnoService;
use App\Infrastructure\Contracts\TurnoRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\TurnoRepository;

/** @param Container $container */
return static function (Container $container): void {
    $container->singleton(\Psr\Log\LoggerInterface::class, static function () {
        $logger = new \Monolog\Logger('app');
        $logger->pushHandler(new \Monolog\Handler\RotatingFileHandler(
            dirname(__DIR__) . '/storage/logs/app.log',
            14,
            \Monolog\Level::Debug
        ));
        return $logger;
    });

    $container->singleton(\PDO::class, static fn() => \App\Infrastructure\Persistence\Database::getConnection());
    $container->singleton(Request::class, static fn() => new Request());

    $container->bind(TaxiRepositoryInterface::class, static fn(Container $c) => new TaxiRepository($c->make(\PDO::class)));
    $container->bind(PropietarioRepositoryInterface::class, static fn(Container $c) => new PropietarioRepository($c->make(\PDO::class)));
    $container->bind(ConductorRepositoryInterface::class, static fn(Container $c) => new ConductorRepository($c->make(\PDO::class)));
    $container->bind(UserRepositoryInterface::class, static fn(Container $c) => new UserRepository($c->make(\PDO::class)));
    $container->bind(AuditLogRepositoryInterface::class, static fn(Container $c) => new AuditLogRepository($c->make(\PDO::class)));

    $container->bind(AuditServiceInterface::class, static fn(Container $c) => new AuditService(
        $c->make(AuditLogRepositoryInterface::class),
        $c->make(Request::class), // Request implements IpProviderInterface
        $c->make(\Psr\Log\LoggerInterface::class),
    ));

    $container->bind(AuthServiceInterface::class, static fn(Container $c) => new AuthService($c->make(UserRepositoryInterface::class)));
    $container->bind(PerfilService::class, static fn(Container $c) => new PerfilService($c->make(UserRepositoryInterface::class)));
    $container->bind(ReporteService::class, static fn(Container $c) => new ReporteService(
        $c->make(TaxiRepositoryInterface::class),
        $c->make(PropietarioRepositoryInterface::class),
        $c->make(ConductorRepositoryInterface::class),
    ));

    $container->bind(TurnoRepositoryInterface::class, static fn(Container $c) => new TurnoRepository($c->make(\PDO::class)));
    $container->bind(TurnoService::class, static fn(Container $c) => new TurnoService(
        $c->make(TurnoRepositoryInterface::class),
        $c->make(ConductorRepositoryInterface::class),
        $c->make(TaxiRepositoryInterface::class),
    ));
};
