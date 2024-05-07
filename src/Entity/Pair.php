<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'pairs')]
#[ORM\Index(fields: ['code'])]
#[ORM\Index(fields: ['currency'])]
#[ORM\Entity(readOnly: true)]
#[ORM\UniqueConstraint(columns: ['date', 'code', 'currency'])]
final class Pair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, updatable: false)]
    private int $id;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true, updatable: false)]
    #[Assert\NotBlank]
    private DateTimeImmutable $date;

    #[ORM\Column(type: Types::STRING, length: 3, updatable: false)]
    #[Assert\NotBlank]
    #[Assert\AtLeastOneOf([
        new Assert\Currency(message: 'The value {{ value }} is not a valid currency'),
        new Assert\Choice(['BTC']),
    ])]
    private string $code;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 6, updatable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'numeric', message: 'The value {{ value }} is not a valid {{ type }}')]
    #[Assert\PositiveOrZero]
    private string $rate;

    #[ORM\Column(type: Types::STRING, length: 3, updatable: false)]
    #[Assert\NotBlank]
    #[Assert\AtLeastOneOf([
        new Assert\Currency(message: 'The value {{ value }} is not a valid currency'),
        new Assert\Choice(['BTC']),
    ])]
    private string $currency;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }
}
