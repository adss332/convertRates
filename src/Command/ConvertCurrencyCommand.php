<?php

declare(strict_types=1);

namespace App\Command;

use App\DTO\ConvertDTO;
use App\Service\Rate\RatesConvertor;
use OutOfBoundsException;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TypeError;
use function count;

#[AsCommand(
    name: 'currency:convert',
    description: 'Convert currency',
)]
class ConvertCurrencyCommand extends Command
{
    public function __construct(private readonly ValidatorInterface $validator, private readonly RatesConvertor $ratesConverter)
    {
        parent::__construct();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this
            ->addArgument('amount', InputArgument::REQUIRED, 'Amount to convert')
            ->addArgument('from', InputArgument::REQUIRED, 'From currency')
            ->addArgument('to', InputArgument::REQUIRED, 'To currency');
    }


    /**
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $amount = $input->getArgument('amount');
        $from = $input->getArgument('from');
        $to = $input->getArgument('to');

        $symfonyIO = new SymfonyStyle($input, $output);

        try {
            $conversion = new ConvertDTO($from, $to, $amount);

            $this->validate($conversion);

            $result = ($this->ratesConverter)($conversion);
        } catch (RuntimeException $exception) {
            $symfonyIO->error('Something went wrong');
            $symfonyIO->text($exception->getMessage());
            $symfonyIO->text("{$exception->getFile()}:{$exception->getLine()}");

            return Command::FAILURE;
        }

        $symfonyIO->success("$amount $from = $result $to");

        return Command::SUCCESS;
    }

    /**
     * @throws OutOfBoundsException
     */
    private function validate(ConvertDTO $conversion): void
    {
        $errors = $this->validator->validate($conversion);
        if (count($errors) > 0) {
            throw new OutOfBoundsException((string)$errors);
        }
    }
}
