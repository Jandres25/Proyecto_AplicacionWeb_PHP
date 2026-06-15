<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Reportes\FiltrosFlota;
use App\Application\Services\ReporteService;
use App\Domain\Models\Conductor;
use App\Domain\Models\Propietario;
use App\Domain\Models\Taxi;
use App\Infrastructure\Contracts\ConductorRepositoryInterface;
use App\Infrastructure\Contracts\PropietarioRepositoryInterface;
use App\Infrastructure\Contracts\TaxiRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ReporteServiceTest extends TestCase
{
    private TaxiRepositoryInterface&MockObject $taxiRepo;
    private PropietarioRepositoryInterface&MockObject $propietarioRepo;
    private ConductorRepositoryInterface&MockObject $conductorRepo;
    private ReporteService $service;

    protected function setUp(): void
    {
        $this->taxiRepo       = $this->createMock(TaxiRepositoryInterface::class);
        $this->propietarioRepo = $this->createMock(PropietarioRepositoryInterface::class);
        $this->conductorRepo  = $this->createMock(ConductorRepositoryInterface::class);

        $this->service = new ReporteService(
            $this->taxiRepo,
            $this->propietarioRepo,
            $this->conductorRepo,
        );
    }

    // --- flota() ---

    #[Test]
    public function flotaCombinaPropietarioYConductorCorrectamente(): void
    {
        $this->taxiRepo->method('all')->willReturn([
            new Taxi(1, 'Corolla', 'Toyota', 10),
        ]);
        $this->propietarioRepo->method('all')->willReturn([
            new Propietario(10, 'Juan Pérez', '3001234567'),
        ]);
        $this->conductorRepo->method('all')->willReturn([
            new Conductor(1, 'Carlos Ruiz', '3109876543', 1),
        ]);

        $rows = $this->service->flota(FiltrosFlota::fromQuery(null, null));

        $this->assertCount(1, $rows);
        $this->assertSame('Juan Pérez', $rows[0]->nombrePropietario);
        $this->assertSame('Carlos Ruiz', $rows[0]->nombreConductor);
    }

    #[Test]
    public function flotaDevuelveNombreConductorNullParaTaxiSinConductor(): void
    {
        $this->taxiRepo->method('all')->willReturn([
            new Taxi(2, 'Aveo', 'Chevrolet', 20),
        ]);
        $this->propietarioRepo->method('all')->willReturn([
            new Propietario(20, 'Ana López', '3001112233'),
        ]);
        $this->conductorRepo->method('all')->willReturn([]);

        $rows = $this->service->flota(FiltrosFlota::fromQuery(null, null));

        $this->assertCount(1, $rows);
        $this->assertNull($rows[0]->nombreConductor);
    }

    #[Test]
    public function flotaFiltraPorMarca(): void
    {
        $this->taxiRepo->method('all')->willReturn([
            new Taxi(1, 'Corolla', 'Toyota', 10),
            new Taxi(2, 'Aveo', 'Chevrolet', 10),
        ]);
        $this->propietarioRepo->method('all')->willReturn([
            new Propietario(10, 'Juan', '3001234567'),
        ]);
        $this->conductorRepo->method('all')->willReturn([]);

        $rows = $this->service->flota(FiltrosFlota::fromQuery('Toyota', null));

        $this->assertCount(1, $rows);
        $this->assertSame('Toyota', $rows[0]->marca);
    }

    #[Test]
    public function flotaFiltraPorPropietario(): void
    {
        $this->taxiRepo->method('all')->willReturn([
            new Taxi(1, 'Corolla', 'Toyota', 10),
            new Taxi(2, 'Aveo', 'Chevrolet', 20),
        ]);
        $this->propietarioRepo->method('all')->willReturn([
            new Propietario(10, 'Juan', '3001234567'),
            new Propietario(20, 'Ana', '3001112233'),
        ]);
        $this->conductorRepo->method('all')->willReturn([]);

        $rows = $this->service->flota(FiltrosFlota::fromQuery(null, '20'));

        $this->assertCount(1, $rows);
        $this->assertSame(2, $rows[0]->placa);
    }

    // --- estadisticas() ---

    #[Test]
    public function estadisticasCalculaTaxisSinConductorCorrectamente(): void
    {
        $this->taxiRepo->method('all')->willReturn([
            new Taxi(1, 'Corolla', 'Toyota', 10),
            new Taxi(2, 'Aveo', 'Chevrolet', 10),
            new Taxi(3, 'Spark', 'Chevrolet', 20),
        ]);
        $this->propietarioRepo->method('all')->willReturn([
            new Propietario(10, 'Juan', '3001234567'),
            new Propietario(20, 'Ana', '3001112233'),
        ]);
        $this->conductorRepo->method('all')->willReturn([
            new Conductor(1, 'Carlos', '3109876543', 1),
        ]);

        $stats = $this->service->estadisticas();

        $this->assertSame(3, $stats->totalTaxis);
        $this->assertSame(2, $stats->totalPropietarios);
        $this->assertSame(1, $stats->totalConductores);
        $this->assertSame(2, $stats->taxisSinConductor);
    }

    // --- marcasDisponibles() ---

    #[Test]
    public function marcasDisponiblesDevuelveMarcasUnicasOrdenadas(): void
    {
        $this->taxiRepo->method('all')->willReturn([
            new Taxi(1, 'Corolla', 'Toyota', 10),
            new Taxi(2, 'Yaris', 'Toyota', 10),
            new Taxi(3, 'Aveo', 'Chevrolet', 20),
        ]);
        $this->propietarioRepo->method('all')->willReturn([]);
        $this->conductorRepo->method('all')->willReturn([]);

        $marcas = $this->service->marcasDisponibles();

        $this->assertSame(['Chevrolet', 'Toyota'], $marcas);
    }

    // --- propietarioOptions() ---

    #[Test]
    public function propietarioOptionsDelegaAlRepo(): void
    {
        $propietarios = [new Propietario(1, 'Juan', '3001234567')];
        $this->propietarioRepo->expects($this->once())->method('all')->willReturn($propietarios);
        $this->taxiRepo->method('all')->willReturn([]);
        $this->conductorRepo->method('all')->willReturn([]);

        $this->assertSame($propietarios, $this->service->propietarioOptions());
    }
}
