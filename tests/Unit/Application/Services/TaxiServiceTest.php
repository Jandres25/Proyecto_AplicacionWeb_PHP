<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\TaxiService;
use App\Domain\Models\Propietario;
use App\Domain\Models\Taxi;
use App\Infrastructure\Contracts\PropietarioRepositoryInterface;
use App\Infrastructure\Contracts\TaxiRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TaxiServiceTest extends TestCase
{
    private TaxiRepositoryInterface&MockObject $taxiRepo;
    private PropietarioRepositoryInterface&MockObject $propietarioRepo;
    private TaxiService $service;

    protected function setUp(): void
    {
        $this->taxiRepo        = $this->createMock(TaxiRepositoryInterface::class);
        $this->propietarioRepo = $this->createMock(PropietarioRepositoryInterface::class);
        $this->service         = new TaxiService($this->taxiRepo, $this->propietarioRepo);
    }

    #[Test]
    public function allWithOwnerMapsNombrePropietario(): void
    {
        $this->taxiRepo->method('all')->willReturn([
            new Taxi(1, 'Corolla', 'Toyota', 10),
            new Taxi(2, 'Aveo', 'Chevrolet', 20),
        ]);
        $this->propietarioRepo->method('all')->willReturn([
            new Propietario(10, 'Juan Pérez', '3001234567'),
            new Propietario(20, 'Ana López', '3109876543'),
        ]);

        $result = $this->service->allWithOwner();

        $this->assertSame('Juan Pérez', $result[0]->nombrePropietario);
        $this->assertSame('Ana López', $result[1]->nombrePropietario);
    }

    #[Test]
    public function allWithOwnerLeavesNombrePropietarioNullWhenNoMatch(): void
    {
        $this->taxiRepo->method('all')->willReturn([
            new Taxi(1, 'Corolla', 'Toyota', 99),
        ]);
        $this->propietarioRepo->method('all')->willReturn([
            new Propietario(10, 'Juan', '3001234567'),
        ]);

        $result = $this->service->allWithOwner();

        $this->assertNull($result[0]->nombrePropietario);
    }

    #[Test]
    public function ownerOptionsDelegatesToRepo(): void
    {
        $propietarios = [new Propietario(1, 'Ana', '3001234567')];
        $this->propietarioRepo->expects($this->once())->method('all')->willReturn($propietarios);

        $this->assertSame($propietarios, $this->service->ownerOptions());
    }

    #[Test]
    public function findByPlacaDelegatesToRepo(): void
    {
        $taxi = new Taxi(5, 'Spark', 'Chevrolet', 1);
        $this->taxiRepo->expects($this->once())->method('findByPlaca')->with(5)->willReturn($taxi);

        $this->assertSame($taxi, $this->service->findByPlaca(5));
    }

    #[Test]
    public function createCallsRepoWhenOwnerExists(): void
    {
        $this->propietarioRepo->method('findById')->with(3)->willReturn(new Propietario(3, 'Test', '3001234567'));
        $this->taxiRepo->expects($this->once())->method('create')->with('Corolla', 'Toyota', 3);

        $this->service->create('Corolla', 'Toyota', 3);
    }

    #[Test]
    public function createThrowsWhenOwnerNotFound(): void
    {
        $this->propietarioRepo->method('findById')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->service->create('Corolla', 'Toyota', 99);
    }

    #[Test]
    public function createThrowsWhenOwnerIdIsZeroOrNegative(): void
    {
        $this->propietarioRepo->expects($this->never())->method('findById');

        $this->expectException(InvalidArgumentException::class);
        $this->service->create('Corolla', 'Toyota', 0);
    }

    #[Test]
    public function updateThrowsWhenPlacaIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->update(0, 'Corolla', 'Toyota', 1);
    }

    #[Test]
    public function updatePropagatesModelValidationError(): void
    {
        $this->propietarioRepo->method('findById')->willReturn(new Propietario(1, 'Test', '3001234567'));

        $this->expectException(InvalidArgumentException::class);
        $this->service->update(1, '', 'Toyota', 1);
    }

    #[Test]
    public function deleteThrowsWhenPlacaIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->delete(-1);
    }

    #[Test]
    public function deleteCallsRepoWithValidPlaca(): void
    {
        $this->taxiRepo->expects($this->once())->method('delete')->with(10);
        $this->service->delete(10);
    }
}
