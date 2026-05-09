<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\ConductorService;
use App\Domain\Models\Conductor;
use App\Domain\Models\Taxi;
use App\Infrastructure\Contracts\ConductorRepositoryInterface;
use App\Infrastructure\Contracts\TaxiRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ConductorServiceTest extends TestCase
{
    private ConductorRepositoryInterface&MockObject $conductorRepo;
    private TaxiRepositoryInterface&MockObject $taxiRepo;
    private ConductorService $service;

    protected function setUp(): void
    {
        $this->conductorRepo = $this->createMock(ConductorRepositoryInterface::class);
        $this->taxiRepo      = $this->createMock(TaxiRepositoryInterface::class);
        $this->service       = new ConductorService($this->conductorRepo, $this->taxiRepo);
    }

    #[Test]
    public function createCallsRepoWhenPlacaExists(): void
    {
        $this->taxiRepo->method('findByPlaca')->with(5)->willReturn(new Taxi(5, 'Spark', 'Chevrolet', 1));
        $this->conductorRepo->expects($this->once())->method('create')->with('Juan Pérez', '3001234567', 5);

        $this->service->create('Juan Pérez', '3001234567', 5);
    }

    #[Test]
    public function createThrowsWhenPlacaNotFound(): void
    {
        $this->taxiRepo->method('findByPlaca')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->service->create('Juan', '3001234567', 99);
    }

    #[Test]
    public function createThrowsWhenPlacaIsZeroOrNegative(): void
    {
        $this->taxiRepo->expects($this->never())->method('findByPlaca');

        $this->expectException(InvalidArgumentException::class);
        $this->service->create('Juan', '3001234567', 0);
    }

    #[Test]
    public function createPropagatesModelValidationErrorForInvalidPhone(): void
    {
        $this->taxiRepo->method('findByPlaca')->willReturn(new Taxi(5, 'Spark', 'Chevrolet', 1));

        $this->expectException(InvalidArgumentException::class);
        $this->service->create('Juan', 'abc', 5);
    }

    #[Test]
    public function updateThrowsWhenIdIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->update(0, 'Juan', '3001234567', 5);
    }

    #[Test]
    public function updateCallsRepoWithValidData(): void
    {
        $this->taxiRepo->method('findByPlaca')->with(5)->willReturn(new Taxi(5, 'Spark', 'Chevrolet', 1));
        $this->conductorRepo->expects($this->once())->method('update')->with(7, 'Juan Pérez', '3001234567', 5);

        $this->service->update(7, 'Juan Pérez', '3001234567', 5);
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
        $this->conductorRepo->expects($this->once())->method('delete')->with(7);
        $this->service->delete(7);
    }

    #[Test]
    public function allDelegatesToRepo(): void
    {
        $conductores = [new Conductor(1, 'Juan', '3001234567', 5)];
        $this->conductorRepo->method('all')->willReturn($conductores);

        $this->assertSame($conductores, $this->service->all());
    }

    #[Test]
    public function taxiOptionsDelegatesToRepo(): void
    {
        $taxis = [new Taxi(5, 'Spark', 'Chevrolet', 1)];
        $this->taxiRepo->method('all')->willReturn($taxis);

        $this->assertSame($taxis, $this->service->taxiOptions());
    }
}
