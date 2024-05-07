<?php
declare(strict_types=1);

namespace App\Tests\Unit\Commands;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Validator\Validation;
use App\Command\ConvertCurrencyCommand;
use App\Service\Rate\RatesConvertor;

class ConvertCurrencyCommandTest extends TestCase
{
    private $commandTester;
    private $ratesConverterMock;

    protected function setUp(): void
    {
        $validator = Validation::createValidator();
        $this->ratesConverterMock = $this->createMock(RatesConvertor::class);

        $command = new ConvertCurrencyCommand($validator, $this->ratesConverterMock);
        $application = new Application();
        $application->add($command);

        $this->commandTester = new CommandTester($application->find('currency:convert'));
    }

    public function testExecuteSuccess()
    {
        $this->ratesConverterMock->method('__invoke')
            ->willReturn('9.00');

        $this->commandTester->execute([
            'amount' => '10',
            'from' => 'USD',
            'to' => 'EUR'
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('10 USD = 9.28 EUR', $output);
    }

    public function testExecuteFailure()
    {
        $this->ratesConverterMock->method('__invoke')
            ->willThrowException(new RuntimeException('Error converting currency'));

        $this->commandTester->execute([
            'amount' => '10',
            'from' => 'USD',
            'to' => 'EUR'
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Something went wrong', $output);
    }
}
