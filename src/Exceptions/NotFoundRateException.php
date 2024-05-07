<?php
declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use UnexpectedValueException;

class NotFoundRateException extends UnexpectedValueException
{
    /**
     * @inheritDoc
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        $message = $message ?: 'No conversion rate for this currencies';

        parent::__construct($message, $code, $previous);
    }
}
