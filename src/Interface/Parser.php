<?php
declare(strict_types=1);

namespace App\Interface;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface Parser
{
    public function parse(ResponseInterface $response): array;
}
