<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\Taxi;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TaxiTest extends TestCase
{
    #[Test]
    public function createReturnsInstanceWithTrimmedValues(): void
    {
        $taxi = Taxi::create('  Corolla  ', '  Toyota  ', 5);

        $this->assertSame('Corolla', $taxi->modelo);
        $this->assertSame('Toyota', $taxi->marca);
        $this->assertSame(5, $taxi->idPropietario);
        $this->assertSame(0, $taxi->placa);
    }

    #[Test]
    public function fromRowHydratesAllFields(): void
    {
        $taxi = Taxi::fromRow([
            'Placa'        => '123',
            'Modelo'       => 'Corolla',
            'Marca'        => 'Toyota',
            'Idpropietario' => '2',
        ]);

        $this->assertSame(123, $taxi->placa);
        $this->assertSame('Corolla', $taxi->modelo);
        $this->assertSame('Toyota', $taxi->marca);
        $this->assertSame(2, $taxi->idPropietario);
        $this->assertNull($taxi->nombrePropietario);
    }

    #[Test]
    public function fromRowAssignsNombrePropietarioWhenPresent(): void
    {
        $taxi = Taxi::fromRow([
            'Placa'         => '1',
            'Modelo'        => 'Aveo',
            'Marca'         => 'Chevrolet',
            'Idpropietario' => '1',
            'propietario'   => 'Juan Pérez',
        ]);

        $this->assertSame('Juan Pérez', $taxi->nombrePropietario);
    }

    #[Test]
    #[DataProvider('invalidModelProvider')]
    public function createThrowsWhenModeloIsInvalid(string $modelo): void
    {
        $this->expectException(InvalidArgumentException::class);

        Taxi::create($modelo, 'Toyota', 1);
    }

    public static function invalidModelProvider(): array
    {
        return [
            'vacío'         => [''],
            'solo espacios' => ['   '],
            'demasiado largo' => [str_repeat('a', 101)],
        ];
    }

    #[Test]
    #[DataProvider('invalidBrandProvider')]
    public function createThrowsWhenMarcaIsInvalid(string $marca): void
    {
        $this->expectException(InvalidArgumentException::class);

        Taxi::create('Corolla', $marca, 1);
    }

    public static function invalidBrandProvider(): array
    {
        return [
            'vacía'          => [''],
            'solo espacios'  => ['   '],
            'demasiado larga' => [str_repeat('b', 101)],
        ];
    }
}
