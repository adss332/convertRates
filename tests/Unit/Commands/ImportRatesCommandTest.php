<?php

namespace App\Tests\Unit\Commands;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use App\Command\ImportRatesCommand;
use App\Service\Rate\RatesImporter;
use App\Service\Rate\RatesTriangulator;

class ImportRatesCommandTest extends TestCase
{
    private $commandTester;
    private $ratesImporterMock;
    private $ratesTriangulatorMock;

    protected function setUp(): void
    {
        $this->ratesImporterMock = $this->createMock(RatesImporter::class);
        $this->ratesTriangulatorMock = $this->createMock(RatesTriangulator::class);

        $command = new ImportRatesCommand($this->ratesImporterMock, $this->ratesTriangulatorMock);
        $application = new Application();
        $application->add($command);

        $this->commandTester = new CommandTester($application->find('rates:import'));
    }

    public function testExecuteSuccess()
    {
        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Currency rates successfully imported', $output);
    }

    public function testExecuteFailure()
    {
        $this->ratesImporterMock->method('__invoke')
            ->willThrowException(new RuntimeException('Failed to import rates'));

        $this->commandTester->execute([]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Something went wrong', $output);
    }
}
