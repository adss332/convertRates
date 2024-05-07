<?php
declare(strict_types=1);

namespace App\Service\Rate;

use App\DTO\RateDTO;
use App\Entity\Rate;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Litipk\BigNumbers\Decimal;
use Symfony\Component\Validator\Exception\OutOfBoundsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function array_merge;
use function count;

class RatesImporter
{
    public function __construct(
        private readonly iterable           $ratesProviders,
        private readonly ValidatorInterface $validator,
        private readonly ManagerRegistry    $doctrine)
    {
    }

    /**
     * @throws OutOfBoundsException
     */
    public function __invoke(): void
    {
        $rates = [];

        foreach ($this->ratesProviders as $ratesProvider) {
            $rates[] = ($ratesProvider)();
        }

        $rates = array_merge(...$rates);
        $this->saveRates($rates);
    }

    /**
     * @throws OutOfBoundsException
     */
    private function getDirectRate(RateDTO $rateDto, ValidatorInterface $validator): Rate
    {
        $rate = new Rate();
        $rate->setDate($rateDto->date);
        $rate->setRate($rateDto->rate);
        $rate->setCode($rateDto->code);
        $rate->setCurrency($rateDto->currency);

        $errors = $validator->validate($rate);
        if (count($errors) > 0) {
            throw new OutOfBoundsException((string)$errors);
        }

        return $rate;
    }


    /**
     * @throws OutOfBoundsException
     */
    private function getReverseRate(RateDTO $rateDto, ValidatorInterface $validator): Rate
    {
        $rateValue = (string)Decimal::fromInteger(1)->div(Decimal::fromString($rateDto->rate), 8);

        $rate = new Rate();
        $rate->setDate($rateDto->date);
        $rate->setCode($rateDto->currency);
        $rate->setRate($rateValue);
        $rate->setCurrency($rateDto->code);

        $errors = $validator->validate($rate);
        if (count($errors) > 0) {
            throw new OutOfBoundsException((string)$errors);
        }

        return $rate;
    }

    /**
     * @param RateDTO[] $rates
     * @throws OutOfBoundsException
     */
    protected function saveRates(array $rates): void
    {
        $entityManager = $this->doctrine->getManager();
        $validator = $this->validator;

        array_walk($rates, function (RateDTO $rateDto) use ($entityManager, $validator): void {
            $rate = $this->getDirectRate($rateDto, $validator);
            $entityManager->persist($rate);

            $rate = $this->getReverseRate($rateDto, $validator);
            $entityManager->persist($rate);
        });

        try {
            $entityManager->flush();
        } /** @noinspection PhpRedundantCatchClauseInspection */
        catch (UniqueConstraintViolationException) {
            $this->doctrine->resetManager();
        }
    }


}
