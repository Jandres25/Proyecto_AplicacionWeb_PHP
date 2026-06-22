<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\TurnoService;
use App\Application\Turnos\TurnoRow;
use App\Domain\Models\Conductor;
use App\Domain\Models\Taxi;
use App\Domain\Models\Turno;
use App\Infrastructure\Contracts\ConductorRepositoryInterface;
use App\Infrastructure\Contracts\TaxiRepositoryInterface;
use App\Infrastructure\Contracts\TurnoRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TurnoServiceTest extends TestCase
{
    private TurnoRepositoryInterface&MockObject     $turnoRepo;
    private ConductorRepositoryInterface&MockObject $conductorRepo;
    private TaxiRepositoryInterface&MockObject      $taxiRepo;
    private TurnoService $service;

    private const INICIO = '2026-06-22 08:00:00';
    private const FIN    = '2026-06-22 16:00:00';

    protected function setUp(): void
    {
        $this->turnoRepo     = $this->createMock(TurnoRepositoryInterface::class);
        $this->conductorRepo = $this->createMock(ConductorRepositoryInterface::class);
        $this->taxiRepo      = $this->createMock(TaxiRepositoryInterface::class);
        $this->service       = new TurnoService($this->turnoRepo, $this->conductorRepo, $this->taxiRepo);
    }

    #[Test]
    public function allReturnsMappedTurnoRows(): void
    {
        $turno     = new Turno(1, 2, 5, self::INICIO, self::FIN);
        $conductor = new Conductor(2, 'Juan Pérez', '3001234567', 5);
        $taxi      = new Taxi(5, 'Spark', 'Chevrolet', 1);

        $this->turnoRepo->method('all')->willReturn([$turno]);
        $this->conductorRepo->method('all')->willReturn([$conductor]);
        $this->taxiRepo->method('all')->willReturn([$taxi]);

        $rows = $this->service->all();

        $this->assertCount(1, $rows);
        $this->assertInstanceOf(TurnoRow::class, $rows[0]);
        $this->assertSame('Juan Pérez', $rows[0]->conductorNombre);
        $this->assertSame('Spark', $rows[0]->taxiModelo);
        $this->assertSame(5, $rows[0]->placa);
    }

    #[Test]
    public function createThrowsWhenConductorNotFound(): void
    {
        $this->conductorRepo->method('findById')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->service->create(99, 5, self::INICIO, self::FIN);
    }

    #[Test]
    public function createThrowsWhenTaxiNotFound(): void
    {
        $this->conductorRepo->method('findById')->willReturn(new Conductor(1, 'Juan', '3001234567', 5));
        $this->taxiRepo->method('findByPlaca')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->service->create(1, 99, self::INICIO, self::FIN);
    }

    #[Test]
    public function createThrowsWhenConductorHasOverlappingTurno(): void
    {
        $this->conductorRepo->method('findById')->willReturn(new Conductor(1, 'Juan', '3001234567', 5));
        $this->taxiRepo->method('findByPlaca')->willReturn(new Taxi(5, 'Spark', 'Chevrolet', 1));
        $this->turnoRepo->method('overlappingForConductor')
            ->willReturn([new Turno(10, 1, 5, self::INICIO, self::FIN)]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El conductor ya tiene un turno asignado en ese horario.');
        $this->service->create(1, 5, self::INICIO, self::FIN);
    }

    #[Test]
    public function createThrowsWhenTaxiHasOverlappingTurno(): void
    {
        $this->conductorRepo->method('findById')->willReturn(new Conductor(1, 'Juan', '3001234567', 5));
        $this->taxiRepo->method('findByPlaca')->willReturn(new Taxi(5, 'Spark', 'Chevrolet', 1));
        $this->turnoRepo->method('overlappingForConductor')->willReturn([]);
        $this->turnoRepo->method('overlappingForPlaca')
            ->willReturn([new Turno(11, 2, 5, self::INICIO, self::FIN)]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El taxi ya está asignado a otro conductor en ese horario.');
        $this->service->create(1, 5, self::INICIO, self::FIN);
    }

    #[Test]
    public function createCallsRepoWhenValid(): void
    {
        $this->conductorRepo->method('findById')->willReturn(new Conductor(1, 'Juan', '3001234567', 5));
        $this->taxiRepo->method('findByPlaca')->willReturn(new Taxi(5, 'Spark', 'Chevrolet', 1));
        $this->turnoRepo->method('overlappingForConductor')->willReturn([]);
        $this->turnoRepo->method('overlappingForPlaca')->willReturn([]);
        $this->turnoRepo->expects($this->once())
            ->method('create')
            ->with(1, 5, self::INICIO, self::FIN)
            ->willReturn(new Turno(1, 1, 5, self::INICIO, self::FIN));

        $result = $this->service->create(1, 5, self::INICIO, self::FIN);

        $this->assertInstanceOf(Turno::class, $result);
    }

    #[Test]
    public function updateThrowsWhenIdIsZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->update(0, 1, 5, self::INICIO, self::FIN);
    }

    #[Test]
    public function updateThrowsWhenTurnoNotFound(): void
    {
        $this->turnoRepo->method('findById')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->service->update(99, 1, 5, self::INICIO, self::FIN);
    }

    #[Test]
    public function updatePassesExcludeIdToOverlapCheck(): void
    {
        $existing = new Turno(7, 1, 5, self::INICIO, self::FIN);
        $this->turnoRepo->method('findById')->willReturn($existing);
        $this->conductorRepo->method('findById')->willReturn(new Conductor(1, 'Juan', '3001234567', 5));
        $this->taxiRepo->method('findByPlaca')->willReturn(new Taxi(5, 'Spark', 'Chevrolet', 1));
        $this->turnoRepo->expects($this->once())
            ->method('overlappingForConductor')
            ->with(1, self::INICIO, self::FIN, 7)
            ->willReturn([]);
        $this->turnoRepo->expects($this->once())
            ->method('overlappingForPlaca')
            ->with(5, self::INICIO, self::FIN, 7)
            ->willReturn([]);
        $this->turnoRepo->expects($this->once())->method('update');

        $this->service->update(7, 1, 5, self::INICIO, self::FIN);
    }

    #[Test]
    public function deleteThrowsWhenIdIsZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->delete(0);
    }

    #[Test]
    public function deleteCallsRepo(): void
    {
        $this->turnoRepo->expects($this->once())->method('delete')->with(3);
        $this->service->delete(3);
    }

    #[Test]
    public function conductorOptionsDelegatesToRepo(): void
    {
        $list = [new Conductor(1, 'Juan', '3001234567', 5)];
        $this->conductorRepo->method('all')->willReturn($list);

        $this->assertSame($list, $this->service->conductorOptions());
    }

    #[Test]
    public function taxiOptionsDelegatesToRepo(): void
    {
        $list = [new Taxi(5, 'Spark', 'Chevrolet', 1)];
        $this->taxiRepo->method('all')->willReturn($list);

        $this->assertSame($list, $this->service->taxiOptions());
    }
}
