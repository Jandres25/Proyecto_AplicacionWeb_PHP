<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\AuthService;
use App\Domain\Models\Usuario;
use App\Infrastructure\Contracts\UserRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class AuthServiceTest extends TestCase
{
    private UserRepositoryInterface&MockObject $repo;
    private AuthService $service;

    protected function setUp(): void
    {
        $_ENV['REMEMBER_ME_TTL_DAYS'] = '30';
        $this->repo    = $this->createMock(UserRepositoryInterface::class);
        $this->service = new AuthService($this->repo);
    }

    protected function tearDown(): void
    {
        unset($_ENV['REMEMBER_ME_TTL_DAYS']);
    }

    private function makeUsuario(string $claveHash): Usuario
    {
        return new Usuario(1, 'Juan', 'Pérez', 'jperez', 'juan@mail.com', $claveHash);
    }

    // --- attempt() ---

    #[Test]
    public function attemptReturnsNullWhenUsernameNotFound(): void
    {
        $this->repo->method('findByUsername')->willReturn(null);

        $this->assertNull($this->service->attempt('noexiste', 'pass'));
    }

    #[Test]
    public function attemptReturnsUserDataWithCorrectHashedPassword(): void
    {
        $hash = password_hash('secreto', PASSWORD_DEFAULT);
        $this->repo->method('findByUsername')->willReturn($this->makeUsuario($hash));
        $this->repo->expects($this->never())->method('updatePasswordHash');

        $result = $this->service->attempt('jperez', 'secreto');

        $this->assertIsArray($result);
        $this->assertSame(1, $result['ID']);
        $this->assertSame('jperez', $result['Usuario']);
    }

    #[Test]
    public function attemptReturnsNullWithWrongHashedPassword(): void
    {
        $hash = password_hash('correcta', PASSWORD_DEFAULT);
        $this->repo->method('findByUsername')->willReturn($this->makeUsuario($hash));

        $this->assertNull($this->service->attempt('jperez', 'incorrecta'));
    }

    #[Test]
    public function attemptUpgradesLegacyPlainTextPassword(): void
    {
        $this->repo->method('findByUsername')->willReturn($this->makeUsuario('claveenplano'));
        $this->repo->expects($this->once())->method('updatePasswordHash')->with(
            1,
            $this->callback(fn(string $hash) => password_verify('claveenplano', $hash))
        );

        $result = $this->service->attempt('jperez', 'claveenplano');

        $this->assertIsArray($result);
    }

    #[Test]
    public function attemptReturnsNullWithWrongLegacyPassword(): void
    {
        $this->repo->method('findByUsername')->willReturn($this->makeUsuario('claveenplano'));

        $this->assertNull($this->service->attempt('jperez', 'otraclaveincorrecta'));
    }

    // --- issueRememberToken() ---

    #[Test]
    public function issueRememberTokenReturnsHexStringOf64Chars(): void
    {
        $this->repo->expects($this->once())->method('updateRememberToken')->with(
            7,
            $this->callback(fn(string $hash) => strlen($hash) === 64 && ctype_xdigit($hash)),
            $this->callback(fn(string $expires) => strtotime($expires) > time())
        );

        $token = $this->service->issueRememberToken(7);

        $this->assertSame(64, strlen($token));
        $this->assertTrue(ctype_xdigit($token));
    }

    // --- consumeRememberToken() ---

    #[Test]
    public function consumeRememberTokenReturnsNullWhenTokenLengthIsNot64(): void
    {
        $this->assertNull($this->service->consumeRememberToken('corto'));
    }

    #[Test]
    public function consumeRememberTokenReturnsNullWhenTokenIsNotHex(): void
    {
        $notHex = str_repeat('z', 64);
        $this->assertNull($this->service->consumeRememberToken($notHex));
    }

    #[Test]
    public function consumeRememberTokenReturnsNullWhenTokenNotFoundInRepo(): void
    {
        $this->repo->method('findByRememberToken')->willReturn(null);

        $validHex = str_repeat('a', 64);
        $this->assertNull($this->service->consumeRememberToken($validHex));
    }

    #[Test]
    public function consumeRememberTokenRotatesTokenAndReturnsData(): void
    {
        $usuario = $this->makeUsuario(password_hash('pass', PASSWORD_DEFAULT));
        $this->repo->method('findByRememberToken')->willReturn($usuario);
        $this->repo->expects($this->once())->method('updateRememberToken')->with(
            1,
            $this->callback(fn(string $hash) => strlen($hash) === 64 && ctype_xdigit($hash)),
            $this->callback(fn(string $expires) => strtotime($expires) > time())
        );

        $validHex = str_repeat('a', 64);
        $result   = $this->service->consumeRememberToken($validHex);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('newPlainToken', $result);
        $this->assertArrayHasKey('expiresAt', $result);
        $this->assertSame(64, strlen($result['newPlainToken']));
    }

    // --- clearRememberToken() ---

    #[Test]
    public function clearRememberTokenCallsRepoWithNulls(): void
    {
        $this->repo->expects($this->once())->method('updateRememberToken')->with(5, null, null);

        $this->service->clearRememberToken(5);
    }
}
