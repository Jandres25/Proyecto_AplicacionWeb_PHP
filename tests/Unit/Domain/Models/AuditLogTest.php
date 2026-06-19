<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\AuditLog;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AuditLogTest extends TestCase
{
    #[Test]
    public function createReturnsInstanceWithTrimmedValues(): void
    {
        $log = AuditLog::create(
            usuarioId: 1,
            usuarioNombre: '  admin  ',
            accion: '  taxi.created  ',
            entidad: '  taxis  ',
            entidadId: '42',
            descripcion: '  Taxi creado  ',
            ip: '127.0.0.1',
        );

        $this->assertSame(0, $log->id);
        $this->assertSame(1, $log->usuarioId);
        $this->assertSame('admin', $log->usuarioNombre);
        $this->assertSame('taxi.created', $log->accion);
        $this->assertSame('taxis', $log->entidad);
        $this->assertSame('42', $log->entidadId);
        $this->assertSame('Taxi creado', $log->descripcion);
        $this->assertSame('127.0.0.1', $log->ip);
    }

    #[Test]
    public function createAcceptsNullUsuarioId(): void
    {
        $log = AuditLog::create(
            usuarioId: null,
            usuarioNombre: '',
            accion: 'auth.login',
            entidad: 'auth',
            entidadId: null,
            descripcion: 'Login exitoso',
            ip: null,
        );

        $this->assertNull($log->usuarioId);
        $this->assertSame('sistema', $log->usuarioNombre);
        $this->assertNull($log->entidadId);
        $this->assertNull($log->ip);
    }

    #[Test]
    #[DataProvider('invalidAccionProvider')]
    public function createThrowsWhenAccionIsInvalid(string $accion): void
    {
        $this->expectException(InvalidArgumentException::class);

        AuditLog::create(null, 'sistema', $accion, 'taxis', null, 'Descripción', null);
    }

    public static function invalidAccionProvider(): array
    {
        return [
            'vacía'         => [''],
            'solo espacios' => ['   '],
        ];
    }

    #[Test]
    #[DataProvider('invalidEntidadProvider')]
    public function createThrowsWhenEntidadIsInvalid(string $entidad): void
    {
        $this->expectException(InvalidArgumentException::class);

        AuditLog::create(null, 'sistema', 'taxi.created', $entidad, null, 'Descripción', null);
    }

    public static function invalidEntidadProvider(): array
    {
        return [
            'vacía'         => [''],
            'solo espacios' => ['   '],
        ];
    }

    #[Test]
    #[DataProvider('invalidDescripcionProvider')]
    public function createThrowsWhenDescripcionIsInvalid(string $descripcion): void
    {
        $this->expectException(InvalidArgumentException::class);

        AuditLog::create(null, 'sistema', 'taxi.created', 'taxis', null, $descripcion, null);
    }

    public static function invalidDescripcionProvider(): array
    {
        return [
            'vacía'         => [''],
            'solo espacios' => ['   '],
        ];
    }

    #[Test]
    public function fromRowHydratesAllFields(): void
    {
        $log = AuditLog::fromRow([
            'id'             => '10',
            'usuario_id'     => '3',
            'usuario_nombre' => 'jandres',
            'accion'         => 'taxi.deleted',
            'entidad'        => 'taxis',
            'entidad_id'     => '99',
            'descripcion'    => 'Taxi eliminado',
            'ip'             => '192.168.1.1',
            'creado_en'      => '2026-06-19 10:00:00',
        ]);

        $this->assertSame(10, $log->id);
        $this->assertSame(3, $log->usuarioId);
        $this->assertSame('jandres', $log->usuarioNombre);
        $this->assertSame('taxi.deleted', $log->accion);
        $this->assertSame('taxis', $log->entidad);
        $this->assertSame('99', $log->entidadId);
        $this->assertSame('Taxi eliminado', $log->descripcion);
        $this->assertSame('192.168.1.1', $log->ip);
        $this->assertSame('2026-06-19 10:00:00', $log->creadoEn);
    }

    #[Test]
    public function fromRowHandlesNullableFields(): void
    {
        $log = AuditLog::fromRow([
            'id'             => '1',
            'usuario_id'     => null,
            'usuario_nombre' => 'sistema',
            'accion'         => 'auth.login',
            'entidad'        => 'auth',
            'entidad_id'     => null,
            'descripcion'    => 'Login exitoso',
            'ip'             => null,
            'creado_en'      => '2026-06-19 09:00:00',
        ]);

        $this->assertNull($log->usuarioId);
        $this->assertNull($log->entidadId);
        $this->assertNull($log->ip);
    }
}
