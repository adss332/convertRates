<?php
declare(strict_types=1);

namespace App\Service\Rate;

use App\DTO\ConvertDTO;
use App\Entity\Pair;
use App\Exceptions\NotFoundRateException;
use Doctrine\Persistence\ManagerRegistry;
use Litipk\BigNumbers\Decimal;

class RatesConvertor
{
    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    public function __invoke(ConvertDTO $conversion): string
    {
        $pair = $this->doctrine->getRepository(Pair::class)->findOneBy([
                'code' => $conversion->from,
                'currency' => $conversion->to,
            ]
        );

        if (!$pair instanceof Pair) {
            throw new NotFoundRateException();
        }

        return (string)Decimal::fromString($pair->getRate())->mul(Decimal::fromString($conversion->amount), 6);
    }
}
