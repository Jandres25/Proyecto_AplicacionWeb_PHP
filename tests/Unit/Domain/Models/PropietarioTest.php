<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\Propietario;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PropietarioTest extends TestCase
{
    #[Test]
    public function createReturnsInstanceWithTrimmedValues(): void
    {
        $propietario = Propietario::create('  Ana López  ', '  3201112233  ');

        $this->assertSame('Ana López', $propietario->nombre);
        $this->assertSame('3201112233', $propietario->telefono);
        $this->assertSame(0, $propietario->id);
    }

    #[Test]
    public function fromRowHydratesAllFields(): void
    {
        $propietario = Propietario::fromRow([
            'Idpropietario' => '3',
            'Nombre'        => 'Carlos Ruiz',
            'Telefono'      => '3001234567',
        ]);

        $this->assertSame(3, $propietario->id);
        $this->assertSame('Carlos Ruiz', $propietario->nombre);
        $this->assertSame('3001234567', $propietario->telefono);
    }

    #[Test]
    #[DataProvider('invalidNameProvider')]
    public function createThrowsWhenNombreIsInvalid(string $nombre): void
    {
        $this->expectException(InvalidArgumentException::class);

        Propietario::create($nombre, '3201112233');
    }

    public static function invalidNameProvider(): array
    {
        return [
            'vacío'           => [''],
            'solo espacios'   => ['   '],
            'demasiado largo' => [str_repeat('a', 256)],
        ];
    }

    #[Test]
    #[DataProvider('invalidPhoneProvider')]
    public function createThrowsWhenTelefonoIsInvalid(string $telefono): void
    {
        $this->expectException(InvalidArgumentException::class);

        Propietario::create('Ana', $telefono);
    }

    public static function invalidPhoneProvider(): array
    {
        return [
            'vacío'           => [''],
            'con letras'      => ['abc1234567'],
            'menos de 7 díg.' => ['123'],
            'más de 10 díg.'  => ['12345678901'],
        ];
    }
}
