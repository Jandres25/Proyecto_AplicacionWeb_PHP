<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\PropietarioService;
use App\Domain\Models\Propietario;
use App\Infrastructure\Contracts\PropietarioRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class PropietarioServiceTest extends TestCase
{
    private PropietarioRepositoryInterface&MockObject $repo;
    private PropietarioService $service;

    protected function setUp(): void
    {
        $this->repo    = $this->createMock(PropietarioRepositoryInterface::class);
        $this->service = new PropietarioService($this->repo);
    }

    #[Test]
    public function allDelegatesToRepo(): void
    {
        $propietarios = [new Propietario(1, 'Ana', '3001234567')];
        $this->repo->method('all')->willReturn($propietarios);

        $this->assertSame($propietarios, $this->service->all());
    }

    #[Test]
    public function findByIdDelegatesToRepo(): void
    {
        $propietario = new Propietario(3, 'Carlos', '3109876543');
        $this->repo->expects($this->once())->method('findById')->with(3)->willReturn($propietario);

        $this->assertSame($propietario, $this->service->findById(3));
    }

    #[Test]
    public function createCallsRepoWithTrimmedValues(): void
    {
        $this->repo->expects($this->once())->method('create')->with('Ana López', '3201112233');

        $this->service->create('  Ana López  ', '  3201112233  ');
    }

    #[Test]
    public function createPropagatesModelValidationError(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->create('', '3001234567');
    }

    #[Test]
    public function updateCallsRepoWithTrimmedValues(): void
    {
        $this->repo->expects($this->once())->method('update')->with(5, 'Carlos Ruiz', '3001234567');

        $this->service->update(5, '  Carlos Ruiz  ', '  3001234567  ');
    }

    #[Test]
    public function updatePropagatesModelValidationError(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->update(1, 'Ana', 'abc');
    }

    #[Test]
    public function deleteThrowsWhenIdIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->delete(0);
    }

    #[Test]
    public function deleteCallsRepoWithValidId(): void
    {
        $this->repo->expects($this->once())->method('delete')->with(3);
        $this->service->delete(3);
    }
}
