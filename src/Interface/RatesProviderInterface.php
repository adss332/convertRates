<?php
declare(strict_types=1);

namespace App\Interface;

use App\DTO\RateDTO;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface RatesProviderInterface
{
    public function __construct(HttpClientInterface $client, string $url, string $base);

    /**
     * @return RateDTO[]
     */
    public function __invoke(): array;
}
