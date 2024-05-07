<?php
declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use UnexpectedValueException;

class FailParsingException extends UnexpectedValueException
{
    /**
     * @inheritDoc
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        $message = $message ?: "parse failed";

        parent::__construct($message, $code, $previous);
    }
}
