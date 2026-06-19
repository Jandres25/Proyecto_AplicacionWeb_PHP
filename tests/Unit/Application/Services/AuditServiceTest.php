<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\AuditService;
use App\Domain\Models\AuditLog;
use App\Infrastructure\Contracts\AuditLogRepositoryInterface;
use App\Presentation\Http\IpProviderInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;

final class AuditServiceTest extends TestCase
{
    private function makeService(
        AuditLogRepositoryInterface $repo,
        ?string $ip = '127.0.0.1',
    ): AuditService {
        $ipProvider = new class ($ip) implements IpProviderInterface {
            public function __construct(private readonly ?string $addr) {}
            public function ip(): ?string { return $this->addr; }
        };

        $logger = $this->createStub(LoggerInterface::class);

        return new AuditService($repo, $ipProvider, $logger);
    }

    #[Test]
    public function logInsertsAuditEntry(): void
    {
        $inserted = null;

        $repo = new class ($inserted) implements AuditLogRepositoryInterface {
            public function __construct(private mixed &$captured) {}

            public function insert(AuditLog $log): void
            {
                $this->captured = $log;
            }

            public function filter(?string $entidad, ?int $usuarioId): array
            {
                return [];
            }
        };

        $service = $this->makeService($repo);
        $service->log('taxi.created', 'taxis', '5', 'Taxi creado: Versa 2023');

        $this->assertInstanceOf(AuditLog::class, $inserted);
        $this->assertSame('taxi.created', $inserted->accion);
        $this->assertSame('taxis', $inserted->entidad);
        $this->assertSame('5', $inserted->entidadId);
        $this->assertSame('Taxi creado: Versa 2023', $inserted->descripcion);
        $this->assertSame('127.0.0.1', $inserted->ip);
    }

    #[Test]
    public function logAcceptsNullEntidadId(): void
    {
        $inserted = null;

        $repo = new class ($inserted) implements AuditLogRepositoryInterface {
            public function __construct(private mixed &$captured) {}

            public function insert(AuditLog $log): void
            {
                $this->captured = $log;
            }

            public function filter(?string $entidad, ?int $usuarioId): array
            {
                return [];
            }
        };

        $service = $this->makeService($repo);
        $service->log('auth.login', 'auth', null, 'Inicio de sesión exitoso');

        $this->assertInstanceOf(AuditLog::class, $inserted);
        $this->assertNull($inserted->entidadId);
    }

    #[Test]
    public function logDoesNotPropagateRepositoryException(): void
    {
        $repo = new class implements AuditLogRepositoryInterface {
            public function insert(AuditLog $log): void
            {
                throw new RuntimeException('DB connection lost');
            }

            public function filter(?string $entidad, ?int $usuarioId): array
            {
                return [];
            }
        };

        $service = $this->makeService($repo);

        // Must not throw
        $service->log('taxi.created', 'taxis', '1', 'Taxi creado');

        $this->addToAssertionCount(1);
    }

    #[Test]
    public function logWritesToLoggerOnRepositoryException(): void
    {
        $repo = new class implements AuditLogRepositoryInterface {
            public function insert(AuditLog $log): void
            {
                throw new RuntimeException('DB down');
            }

            public function filter(?string $entidad, ?int $usuarioId): array
            {
                return [];
            }
        };

        $ipProvider = new class implements IpProviderInterface {
            public function ip(): ?string { return '10.0.0.1'; }
        };

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error')
            ->with(
                $this->identicalTo('AuditService::log falló'),
                $this->arrayHasKey('error'),
            );

        $service = new AuditService($repo, $ipProvider, $logger);
        $service->log('conductor.deleted', 'conductores', '3', 'Conductor eliminado');
    }

    #[Test]
    public function logUsesNullIpWhenNotAvailable(): void
    {
        $inserted = null;

        $repo = new class ($inserted) implements AuditLogRepositoryInterface {
            public function __construct(private mixed &$captured) {}

            public function insert(AuditLog $log): void
            {
                $this->captured = $log;
            }

            public function filter(?string $entidad, ?int $usuarioId): array
            {
                return [];
            }
        };

        $service = $this->makeService($repo, null);
        $service->log('auth.logout', 'auth', null, 'Cierre de sesión');

        $this->assertNull($inserted->ip);
    }
}
