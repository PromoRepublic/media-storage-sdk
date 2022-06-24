<?php

declare(strict_types=1);

namespace Promorepublic\MediaStorageClient\Shared;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Promorepublic\MediaStorageClient\ExceptionConverter;
use Promorepublic\MediaStorageClient\Shared\Exception\MediaStorageClientException;
use Promorepublic\MediaStorageClient\Shared\Exception\MediaStorageStorageClientServerException;
use Promorepublic\MediaStorageClient\Shared\Exception\MediaStorageStorageClientUnknownException;

final class MediaStorageHttpClient
{
    private string $uploadEndpoint = 'upload';

    private Client $httpClient;

    private string $baseUrl;

    public function __construct(string $apiKey, string $baseUrl = "https://media-storage.promorepublic.com")
    {
        $this->baseUrl = $baseUrl;

        $this->httpClient = new Client([
            'headers' => [
                'x-api-key' => $apiKey,
            ]
        ]);
    }

    /**
     * @param string $url
     * @return string
     * @throws MediaStorageClientException
     */
    public function uploadFacebookImage(string $url): string
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->baseUrl . "/$this->uploadEndpoint",
                [
                    'json' => ['url' => $url],
                ]
            );

            return json_decode($response->getBody()->getContents())->path;
        } catch (ClientException $clientException) {
            $errorResponse = json_decode($clientException->getResponse()->getBody()->getContents());

            throw ExceptionConverter::convert(
                $errorResponse->message,
                $errorResponse->code ?? null
            );

        } catch (ServerException $serverException) {
            throw new MediaStorageStorageClientServerException($serverException->getMessage());
        } catch (\Throwable $unknownException) {
            throw new MediaStorageStorageClientUnknownException($unknownException->getMessage());
        }
    }
}
