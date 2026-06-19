<?php

declare(strict_types=1);

namespace App\Presentation\Http;

interface IpProviderInterface
{
    public function ip(): ?string;
}
