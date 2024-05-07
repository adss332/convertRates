<?php
declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;
use Exception;
use Litipk\BigNumbers\Decimal;

final readonly class RateDTO
{
    public DateTimeImmutable $date;
    public string $rate;

    /**
     * @throws Exception
     */
    public function __construct(
        string        $date,
        string        $rate,
        public string $currency,
        public string $code,
    )
    {
        $this->rate = (string)Decimal::create($rate, 6);
        $this->date = new DateTimeImmutable($date);
    }
}
