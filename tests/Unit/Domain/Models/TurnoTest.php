<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\Turno;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TurnoTest extends TestCase
{
    #[Test]
    public function createReturnsValidInstance(): void
    {
        $turno = Turno::create(1, 100, '2026-06-22 08:00:00', '2026-06-22 16:00:00');

        $this->assertSame(0, $turno->id);
        $this->assertSame(1, $turno->conductorId);
        $this->assertSame(100, $turno->placa);
        $this->assertSame('2026-06-22 08:00:00', $turno->inicio);
        $this->assertSame('2026-06-22 16:00:00', $turno->fin);
    }

    #[Test]
    public function fromRowHydratesAllFields(): void
    {
        $turno = Turno::fromRow([
            'id'           => '5',
            'conductor_id' => '2',
            'placa'        => '99',
            'inicio'       => '2026-06-22 08:00:00',
            'fin'          => '2026-06-22 16:00:00',
        ]);

        $this->assertSame(5, $turno->id);
        $this->assertSame(2, $turno->conductorId);
        $this->assertSame(99, $turno->placa);
        $this->assertSame('2026-06-22 08:00:00', $turno->inicio);
        $this->assertSame('2026-06-22 16:00:00', $turno->fin);
    }

    #[Test]
    public function createThrowsWhenConductorIdIsZeroOrNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Turno::create(0, 100, '2026-06-22 08:00:00', '2026-06-22 16:00:00');
    }

    #[Test]
    public function createThrowsWhenPlacaIsZeroOrNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Turno::create(1, 0, '2026-06-22 08:00:00', '2026-06-22 16:00:00');
    }

    #[Test]
    #[DataProvider('invalidDatetimeProvider')]
    public function createThrowsWhenInicioIsInvalid(string $inicio): void
    {
        $this->expectException(InvalidArgumentException::class);

        Turno::create(1, 100, $inicio, '2026-06-22 16:00:00');
    }

    public static function invalidDatetimeProvider(): array
    {
        return [
            'vacío'           => [''],
            'solo fecha'      => ['2026-06-22'],
            'formato inválido' => ['22/06/2026 08:00'],
            'texto libre'     => ['hoy a las 8'],
        ];
    }

    #[Test]
    #[DataProvider('invalidDatetimeProvider')]
    public function createThrowsWhenFinIsInvalid(string $fin): void
    {
        $this->expectException(InvalidArgumentException::class);

        Turno::create(1, 100, '2026-06-22 08:00:00', $fin);
    }

    #[Test]
    public function createThrowsWhenFinEqualsInicio(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Turno::create(1, 100, '2026-06-22 08:00:00', '2026-06-22 08:00:00');
    }

    #[Test]
    public function createThrowsWhenFinIsBeforeInicio(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Turno::create(1, 100, '2026-06-22 16:00:00', '2026-06-22 08:00:00');
    }

    #[Test]
    public function createAllowsOvernightShift(): void
    {
        $turno = Turno::create(1, 100, '2026-06-22 22:00:00', '2026-06-23 06:00:00');

        $this->assertSame('2026-06-22 22:00:00', $turno->inicio);
        $this->assertSame('2026-06-23 06:00:00', $turno->fin);
    }
}
