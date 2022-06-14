<?php

declare(strict_types=1);

namespace Promorepublic\MediaStorage;

use Promorepublic\MediaStorage\Shared\MediaStorageHttpClientConfigurationBag;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MediaStorageHttpClient
{
    private string $uploadEndpoint = 'upload';

    private HttpClientInterface $httpClient;

    private MediaStorageHttpClientConfigurationBag $configurationBag;

    public function __construct(MediaStorageHttpClientConfigurationBag $configurationBag)
    {
        $this->configurationBag = $configurationBag;

        $this->httpClient = HttpClient::create([
            'headers' => [
                'x-api-key' => $configurationBag->apiKey,
            ]
        ]);
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function uploadFacebookImage(string $url): string
    {
        $response = $this->httpClient->request(
            'POST',
            $this->configurationBag->mediaStorageUrl . "/$this->uploadEndpoint",
            [
                'json' => ['url' => $url],
            ]
        );

        // Would be nice to check if 'path' key exists and throw an error if doesn't
        return json_decode($response->getContent(), true)['path'];
    }
}