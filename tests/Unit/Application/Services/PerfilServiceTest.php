<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\PerfilService;
use App\Domain\Models\Usuario;
use App\Infrastructure\Contracts\UserRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class PerfilServiceTest extends TestCase
{
    private UserRepositoryInterface&MockObject $repo;
    private PerfilService $service;

    private function makeUsuario(int $id = 1, string $correo = 'juan@mail.com', ?string $claveHash = null): Usuario
    {
        return new Usuario(
            id: $id,
            nombres: 'Juan',
            apellidos: 'Pérez',
            usuario: 'jperez',
            correo: $correo,
            claveHash: $claveHash ?? password_hash('clave123', PASSWORD_DEFAULT),
        );
    }

    protected function setUp(): void
    {
        $this->repo    = $this->createMock(UserRepositoryInterface::class);
        $this->service = new PerfilService($this->repo);
    }

    // --- findById ---

    #[Test]
    public function findByIdDelegatesToRepo(): void
    {
        $usuario = $this->makeUsuario();
        $this->repo->method('findById')->with(1)->willReturn($usuario);

        $this->assertSame($usuario, $this->service->findById(1));
    }

    // --- updateProfile ---

    #[Test]
    public function updateProfileThrowsWhenUserNotFound(): void
    {
        $this->repo->method('findById')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Usuario no encontrado.');

        $this->service->updateProfile(1, 'Juan', 'Pérez', 'juan@mail.com');
    }

    #[Test]
    public function updateProfileThrowsWhenEmailTakenByAnotherUser(): void
    {
        $this->repo->method('findById')->willReturn($this->makeUsuario());
        $this->repo->method('existsEmail')->willReturn(true);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ya está registrado por otro usuario');

        $this->service->updateProfile(1, 'Juan', 'Pérez', 'otro@mail.com');
    }

    #[Test]
    public function updateProfileCallsUpdatePreservingUsername(): void
    {
        $this->repo->method('findById')->willReturn($this->makeUsuario());
        $this->repo->method('existsEmail')->willReturn(false);
        $this->repo->expects($this->once())->method('update')->with(
            1, 'Carlos', 'López', 'jperez', 'carlos@mail.com'
        );

        $this->service->updateProfile(1, 'Carlos', 'López', 'carlos@mail.com');
    }

    #[Test]
    public function updateProfileAllowsSameEmailAsOwn(): void
    {
        $this->repo->method('findById')->willReturn($this->makeUsuario(correo: 'juan@mail.com'));
        $this->repo->method('existsEmail')->with('juan@mail.com', 1)->willReturn(false);
        $this->repo->expects($this->once())->method('update');

        $this->service->updateProfile(1, 'Juan', 'Pérez', 'juan@mail.com');
    }

    // --- updatePassword ---

    #[Test]
    public function updatePasswordThrowsWhenUserNotFound(): void
    {
        $this->repo->method('findById')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Usuario no encontrado.');

        $this->service->updatePassword(1, 'clave123', 'nuevaclave1', 'nuevaclave1');
    }

    #[Test]
    public function updatePasswordThrowsWhenCurrentPasswordIsWrong(): void
    {
        $this->repo->method('findById')->willReturn($this->makeUsuario());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('contraseña actual no es correcta');

        $this->service->updatePassword(1, 'incorrecta', 'nuevaclave1', 'nuevaclave1');
    }

    #[Test]
    public function updatePasswordThrowsWhenNewPasswordTooShort(): void
    {
        $this->repo->method('findById')->willReturn($this->makeUsuario());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('al menos 8 caracteres');

        $this->service->updatePassword(1, 'clave123', 'corta', 'corta');
    }

    #[Test]
    public function updatePasswordThrowsWhenConfirmationDoesNotMatch(): void
    {
        $this->repo->method('findById')->willReturn($this->makeUsuario());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('no coinciden');

        $this->service->updatePassword(1, 'clave123', 'nuevaclave1', 'diferente1');
    }

    #[Test]
    public function updatePasswordCallsUpdatePasswordHashWithHashedValue(): void
    {
        $this->repo->method('findById')->willReturn($this->makeUsuario());
        $this->repo->expects($this->once())->method('updatePasswordHash')->with(
            1,
            $this->callback(fn(string $hash) => password_verify('nuevaclave1', $hash))
        );

        $this->service->updatePassword(1, 'clave123', 'nuevaclave1', 'nuevaclave1');
    }
}
