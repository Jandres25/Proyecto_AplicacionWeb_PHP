<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\Usuario;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class UsuarioValidatePasswordChangeTest extends TestCase
{
    #[Test]
    public function validatePasswordChangeSucceedsWithValidInput(): void
    {
        $this->expectNotToPerformAssertions();
        Usuario::validatePasswordChange('nuevaclave123', 'nuevaclave123');
    }

    #[Test]
    #[DataProvider('tooShortProvider')]
    public function validatePasswordChangeThrowsWhenTooShort(string $nueva): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('al menos 8 caracteres');

        Usuario::validatePasswordChange($nueva, $nueva);
    }

    public static function tooShortProvider(): array
    {
        return [
            'vacía'      => [''],
            '1 caracter' => ['a'],
            '7 caracteres' => ['abcdefg'],
        ];
    }

    #[Test]
    public function validatePasswordChangeThrowsWhenConfirmationDoesNotMatch(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('no coinciden');

        Usuario::validatePasswordChange('nuevaclave123', 'diferente123');
    }

    #[Test]
    public function validatePasswordChangeAllowsExactlyEightChars(): void
    {
        $this->expectNotToPerformAssertions();
        Usuario::validatePasswordChange('12345678', '12345678');
    }
}
