<?php

declare(strict_types=1);

use App\Infrastructure\Contracts\ConductorRepositoryInterface;
use App\Infrastructure\Contracts\PropietarioRepositoryInterface;
use App\Infrastructure\Contracts\TaxiRepositoryInterface;
use App\Infrastructure\Contracts\UserRepositoryInterface;
use App\Application\Contracts\AuthServiceInterface;
use App\Core\Container;
use App\Presentation\Http\Request;
use App\Infrastructure\Persistence\Repositories\ConductorRepository;
use App\Infrastructure\Persistence\Repositories\PropietarioRepository;
use App\Infrastructure\Persistence\Repositories\TaxiRepository;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Application\Services\AuthService;

/** @param Container $container */
return static function (Container $container): void {
    $container->singleton(\PDO::class, static fn() => \App\Infrastructure\Persistence\Database::getConnection());
    $container->singleton(Request::class, static fn() => new Request());

    $container->bind(TaxiRepositoryInterface::class, static fn(Container $c) => new TaxiRepository($c->make(\PDO::class)));
    $container->bind(PropietarioRepositoryInterface::class, static fn(Container $c) => new PropietarioRepository($c->make(\PDO::class)));
    $container->bind(ConductorRepositoryInterface::class, static fn(Container $c) => new ConductorRepository($c->make(\PDO::class)));
    $container->bind(UserRepositoryInterface::class, static fn(Container $c) => new UserRepository($c->make(\PDO::class)));

    $container->bind(AuthServiceInterface::class, static fn(Container $c) => new AuthService($c->make(UserRepositoryInterface::class)));
};
