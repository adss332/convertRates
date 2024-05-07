<?php
declare(strict_types=1);

namespace App\Service\Rate;

use App\Entity\Pair;
use App\Entity\Rate;
use Doctrine\Persistence\ManagerRegistry;

class RatesTriangulator
{
    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    public function __invoke(): void
    {
        $ratesTable = $this->doctrine->getManager()->getClassMetadata(Rate::class)->getTableName();
        $pairsTable = $this->doctrine->getManager()->getClassMetadata(Pair::class)->getTableName();

        $sqlInsertRates = "INSERT INTO $pairsTable (date, code, currency, rate)
        SELECT date, code, currency, rate FROM $ratesTable
        ON CONFLICT (code, currency) DO NOTHING";

        $sqlInsertDerived = "INSERT INTO $pairsTable (date, code, currency, rate)
        SELECT DISTINCT derived.date, derived.code, derived.currency, derived.rate
        FROM (SELECT f.date AS date, f.code AS code, t.code AS currency, round((f.rate / t.rate)::numeric, 8) AS rate
        FROM $ratesTable AS f
        JOIN $ratesTable AS t ON f.currency = t.currency) AS derived
        LEFT JOIN $pairsTable AS existing ON derived.code = existing.code AND derived.currency = existing.currency
        WHERE existing.code IS NULL
        ON CONFLICT (code, currency) DO NOTHING";

        $this->doctrine->getConnection()->executeStatement($sqlInsertRates);
        $this->doctrine->getConnection()->executeStatement($sqlInsertDerived);
    }
}
