<?php
declare(strict_types=1);

namespace App\Provider;

use App\DTO\RateDTO;
use Exception;

final class CoindeskRatesProvider extends RatesProvider
{
    const CURRENCY = 'USD';
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function getRates(array $data): array
    {
        $rates = $data['bpi'];
        $rate = (string)$rates[self::CURRENCY]['rate_float'];
        return [
            new RateDTO(
                date('Y-m-d'),
                $rate,
                self::CURRENCY,
                $this->code,
            ),
        ];
    }
}
