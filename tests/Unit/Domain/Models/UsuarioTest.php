<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\Usuario;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class UsuarioTest extends TestCase
{
    #[Test]
    public function createReturnsInstanceWithTrimmedValues(): void
    {
        $usuario = Usuario::create('  Juan  ', '  Pérez  ', '  jperez  ', '  juan@mail.com  ');

        $this->assertSame('Juan', $usuario->nombres);
        $this->assertSame('Pérez', $usuario->apellidos);
        $this->assertSame('jperez', $usuario->usuario);
        $this->assertSame('juan@mail.com', $usuario->correo);
        $this->assertSame(0, $usuario->id);
        $this->assertSame('', $usuario->claveHash);
    }

    #[Test]
    public function fromRowHydratesAllFieldsWithoutRememberToken(): void
    {
        $usuario = Usuario::fromRow([
            'ID'        => '10',
            'Nombres'   => 'María',
            'Apellidos' => 'Gómez',
            'Usuario'   => 'mgomez',
            'Correo'    => 'maria@mail.com',
            'Clave'     => 'hashsecreto',
        ]);

        $this->assertSame(10, $usuario->id);
        $this->assertSame('María', $usuario->nombres);
        $this->assertSame('hashsecreto', $usuario->claveHash);
        $this->assertNull($usuario->rememberToken);
        $this->assertNull($usuario->rememberTokenExpires);
    }

    #[Test]
    public function fromRowHydratesRememberTokenWhenPresent(): void
    {
        $usuario = Usuario::fromRow([
            'ID'                    => '1',
            'Nombres'               => 'Test',
            'Apellidos'             => 'User',
            'Usuario'               => 'tuser',
            'Correo'                => 'test@mail.com',
            'Clave'                 => 'hash',
            'remember_token'        => 'abc123',
            'remember_token_expires' => '2026-06-01 00:00:00',
        ]);

        $this->assertSame('abc123', $usuario->rememberToken);
        $this->assertSame('2026-06-01 00:00:00', $usuario->rememberTokenExpires);
    }

    #[Test]
    #[DataProvider('invalidNombresProvider')]
    public function createThrowsWhenNombresIsInvalid(string $nombres): void
    {
        $this->expectException(InvalidArgumentException::class);

        Usuario::create($nombres, 'Pérez', 'jperez', 'juan@mail.com');
    }

    public static function invalidNombresProvider(): array
    {
        return [
            'vacío'           => [''],
            'solo espacios'   => ['   '],
            'demasiado largo' => [str_repeat('a', 101)],
        ];
    }

    #[Test]
    #[DataProvider('invalidApellidosProvider')]
    public function createThrowsWhenApellidosIsInvalid(string $apellidos): void
    {
        $this->expectException(InvalidArgumentException::class);

        Usuario::create('Juan', $apellidos, 'jperez', 'juan@mail.com');
    }

    public static function invalidApellidosProvider(): array
    {
        return [
            'vacío'           => [''],
            'solo espacios'   => ['   '],
            'demasiado largo' => [str_repeat('a', 101)],
        ];
    }

    #[Test]
    #[DataProvider('invalidUsuarioProvider')]
    public function createThrowsWhenUsuarioIsInvalid(string $usuario): void
    {
        $this->expectException(InvalidArgumentException::class);

        Usuario::create('Juan', 'Pérez', $usuario, 'juan@mail.com');
    }

    public static function invalidUsuarioProvider(): array
    {
        return [
            'vacío'           => [''],
            'solo espacios'   => ['   '],
            'demasiado largo' => [str_repeat('u', 51)],
        ];
    }

    #[Test]
    #[DataProvider('invalidCorreoProvider')]
    public function createThrowsWhenCorreoIsInvalid(string $correo): void
    {
        $this->expectException(InvalidArgumentException::class);

        Usuario::create('Juan', 'Pérez', 'jperez', $correo);
    }

    public static function invalidCorreoProvider(): array
    {
        return [
            'vacío'           => [''],
            'solo espacios'   => ['   '],
            'sin arroba'      => ['no-es-email'],
            'demasiado largo' => [str_repeat('a', 90) . '@mail.com'],
        ];
    }
}
