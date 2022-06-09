<?php

declare(strict_types=1);

namespace Promorepublic\MediaStorage;

use Promorepublic\MediaStorage\Shared\MediaStorageHttpClientConfigurationBag;
use Promorepublic\MediaStorage\Shared\MediaUrl;
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
    public function uploadFacebookImage(string $url): MediaUrl
    {
        $response = $this->httpClient->request(
            'POST',
            $this->configurationBag->mediaStorageUrl . "/$this->uploadEndpoint",
            [
                'body' => ['url' => $url],
            ]
        );

        return new MediaUrl(json_decode($response->getContent(), true));
    }
}