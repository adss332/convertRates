<?php
declare(strict_types=1);

namespace App\Provider;

use App\DTO\RateDTO;
use Exception;
use function array_map;

final class EcbRatesProvider extends RatesProvider
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function getRates(array $data): array
    {
        $root = $data['Cube']['Cube'];

        $date = $root['@attributes']['time'];
        $code = $this->code;

        $rates = $root['Cube'];

        return array_map(
            static fn(array $rate): RateDTO => new RateDTO(
                $date,
                (string)$rate['@attributes']['rate'],
                $rate['@attributes']['currency'],
                $code,
            ),
            $rates
        );
    }
}
