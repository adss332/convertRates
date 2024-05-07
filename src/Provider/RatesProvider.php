<?php
declare(strict_types=1);

namespace App\Provider;

use App\DTO\RateDTO;
use App\Exceptions\FailParsingException;
use App\Interface\RatesProviderInterface;
use App\Service\Parser\JsonParser;
use App\Service\Parser\XmlParser;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class RatesProvider implements RatesProviderInterface
{
    final public function __construct(private readonly HttpClientInterface $client, protected readonly string $url, protected readonly string $code)
    {
    }

    /**
     * @inheritDoc
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function __invoke(): array
    {
        $data = $this->fetch($this->url);
        $data = $this->parse($data);
        return $this->getRates($data);
    }


    /**
     * @throws TransportExceptionInterface
     */
    protected function fetch(string $url): ResponseInterface
    {
        return $this->client->request('GET', $url);
    }


    /**
     * @throws ServerExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function parse(ResponseInterface $response): array
    {
        $contentType = $response->getHeaders()['content-type'][0];

        $parser = match ($contentType) {
            'application/json; charset=utf-8' => JsonParser::class,
            'text/xml' => XmlParser::class,
            default => throw new FailParsingException(),
        };

        return (new $parser())->parse($response);
    }


    /**
     * @return RateDTO[]
     * @throws Exception
     */
    protected function getRates(array $data): array
    {
        return array_map(
            static fn(array $rate): RateDTO => new RateDTO(
                $rate['date'],
                $rate['rate'],
                $rate['currency'],
                $rate['code'],
            ),
            $data
        );
    }
}
