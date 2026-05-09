<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\UsuarioService;
use App\Domain\Models\Usuario;
use App\Infrastructure\Contracts\UserRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class UsuarioServiceTest extends TestCase
{
    private UserRepositoryInterface&MockObject $repo;
    private UsuarioService $service;

    protected function setUp(): void
    {
        $this->repo    = $this->createMock(UserRepositoryInterface::class);
        $this->service = new UsuarioService($this->repo);
    }

    #[Test]
    public function createCallsRepoWithHashedPassword(): void
    {
        $this->repo->method('existsUsername')->willReturn(false);
        $this->repo->method('existsEmail')->willReturn(false);
        $this->repo->expects($this->once())->method('create')->with(
            'Juan',
            'Pérez',
            'jperez',
            $this->callback(fn(string $hash) => password_verify('clave123', $hash)),
            'juan@mail.com'
        );

        $this->service->create('Juan', 'Pérez', 'jperez', 'clave123', 'juan@mail.com');
    }

    #[Test]
    public function createThrowsWhenClaveIsEmpty(): void
    {
        $this->repo->expects($this->never())->method('create');

        $this->expectException(InvalidArgumentException::class);
        $this->service->create('Juan', 'Pérez', 'jperez', '', 'juan@mail.com');
    }

    #[Test]
    public function createThrowsWhenUsernameAlreadyExists(): void
    {
        $this->repo->method('existsUsername')->willReturn(true);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El nombre de usuario ya existe.');
        $this->service->create('Juan', 'Pérez', 'jperez', 'clave123', 'juan@mail.com');
    }

    #[Test]
    public function createThrowsWhenEmailAlreadyExists(): void
    {
        $this->repo->method('existsUsername')->willReturn(false);
        $this->repo->method('existsEmail')->willReturn(true);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El correo ya está registrado.');
        $this->service->create('Juan', 'Pérez', 'jperez', 'clave123', 'juan@mail.com');
    }

    #[Test]
    public function updateThrowsWhenIdIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->update(0, 'Juan', 'Pérez', 'jperez', '', 'juan@mail.com');
    }

    #[Test]
    public function updateDoesNotCallUpdatePasswordHashWhenClaveIsEmpty(): void
    {
        $this->repo->method('existsUsername')->willReturn(false);
        $this->repo->method('existsEmail')->willReturn(false);
        $this->repo->expects($this->never())->method('updatePasswordHash');
        $this->repo->expects($this->once())->method('update');

        $this->service->update(1, 'Juan', 'Pérez', 'jperez', '', 'juan@mail.com');
    }

    #[Test]
    public function updateCallsUpdatePasswordHashWhenClaveIsProvided(): void
    {
        $this->repo->method('existsUsername')->willReturn(false);
        $this->repo->method('existsEmail')->willReturn(false);
        $this->repo->expects($this->once())->method('updatePasswordHash')->with(
            1,
            $this->callback(fn(string $hash) => password_verify('nuevaclave', $hash))
        );

        $this->service->update(1, 'Juan', 'Pérez', 'jperez', 'nuevaclave', 'juan@mail.com');
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
        $this->repo->expects($this->once())->method('delete')->with(5);
        $this->service->delete(5);
    }

    #[Test]
    public function allDelegatesToRepo(): void
    {
        $usuarios = [new Usuario(1, 'Juan', 'Pérez', 'jperez', 'juan@mail.com', 'hash')];
        $this->repo->method('all')->willReturn($usuarios);

        $this->assertSame($usuarios, $this->service->all());
    }
}
