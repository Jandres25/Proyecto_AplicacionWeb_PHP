<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\Conductor;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConductorTest extends TestCase
{
    #[Test]
    public function createReturnsInstanceWithTrimmedValues(): void
    {
        $conductor = Conductor::create('  Juan Pérez  ', '3001234567', 100);

        $this->assertSame('Juan Pérez', $conductor->nombres);
        $this->assertSame('3001234567', $conductor->telefono);
        $this->assertSame(100, $conductor->placa);
        $this->assertSame(0, $conductor->id);
    }

    #[Test]
    public function fromRowHydratesAllFields(): void
    {
        $conductor = Conductor::fromRow([
            'ID'       => '7',
            'Nombres'  => 'Ana García',
            'Telefono' => '3109876543',
            'Placa'    => '55',
        ]);

        $this->assertSame(7, $conductor->id);
        $this->assertSame('Ana García', $conductor->nombres);
        $this->assertSame('3109876543', $conductor->telefono);
        $this->assertSame(55, $conductor->placa);
    }

    #[Test]
    #[DataProvider('invalidNameProvider')]
    public function createThrowsWhenNombresIsInvalid(string $nombres): void
    {
        $this->expectException(InvalidArgumentException::class);

        Conductor::create($nombres, '3001234567', 1);
    }

    public static function invalidNameProvider(): array
    {
        return [
            'vacío'          => [''],
            'solo espacios'  => ['   '],
            'demasiado largo' => [str_repeat('a', 256)],
        ];
    }

    #[Test]
    #[DataProvider('invalidPhoneProvider')]
    public function createThrowsWhenTelefonoIsInvalid(string $telefono): void
    {
        $this->expectException(InvalidArgumentException::class);

        Conductor::create('Juan', $telefono, 1);
    }

    public static function invalidPhoneProvider(): array
    {
        return [
            'vacío'           => [''],
            'con letras'      => ['300abc4567'],
            'menos de 7 díg.' => ['12345'],
            'más de 10 díg.'  => ['12345678901'],
            'con guiones'     => ['300-123-4567'],
        ];
    }
}
