<?php

declare(strict_types=1);

use App\Contracts\Repositories\ConductorRepositoryInterface;
use App\Contracts\Repositories\PropietarioRepositoryInterface;
use App\Contracts\Repositories\TaxiRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\AuthServiceInterface;
use App\Core\Container;
use App\Presentation\Http\Request;
use App\Repositories\ConductorRepository;
use App\Repositories\PropietarioRepository;
use App\Repositories\TaxiRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;

/** @param Container $container */
return static function (Container $container): void {
    $container->singleton(\PDO::class, static fn() => \App\Core\Database::getConnection());
    $container->singleton(Request::class, static fn() => new Request());

    $container->bind(TaxiRepositoryInterface::class, static fn(Container $c) => new TaxiRepository($c->make(\PDO::class)));
    $container->bind(PropietarioRepositoryInterface::class, static fn(Container $c) => new PropietarioRepository($c->make(\PDO::class)));
    $container->bind(ConductorRepositoryInterface::class, static fn(Container $c) => new ConductorRepository($c->make(\PDO::class)));
    $container->bind(UserRepositoryInterface::class, static fn(Container $c) => new UserRepository($c->make(\PDO::class)));

    $container->bind(AuthServiceInterface::class, static fn(Container $c) => new AuthService($c->make(UserRepositoryInterface::class)));
};
