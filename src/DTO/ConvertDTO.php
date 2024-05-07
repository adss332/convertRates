<?php
declare(strict_types=1);

namespace App\DTO;

use Litipk\BigNumbers\Decimal;
use Symfony\Component\Validator\Constraints as Assert;
use TypeError;

final class ConvertDTO
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'numeric')]
    #[Assert\Positive]
    public string $amount;

    /**
     * @throws TypeError
     */
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $from,

        #[Assert\NotBlank]
        public readonly string $to,

        string $amount
    ) {
        $this->amount = (string)Decimal::create($amount, 8);
    }
}
