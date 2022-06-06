<?php

declare(strict_types=1);

namespace Promorepublic\MediaStorage;

use Promorepublic\MediaStorage\Shared\MediaUrl;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MediaStorageHttpClient
{
    private string $uploadEndpoint = 'upload';

    private HttpClientInterface $httpClient;

    private string $mediaStorageUrl;

    public function __construct(HttpClientInterface $httpClient, string $mediaStorageUrl = "0.0.0.0:8000")
    {
        $this->httpClient = $httpClient;
        $this->mediaStorageUrl = $mediaStorageUrl;
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
            "$this->mediaStorageUrl/$this->uploadEndpoint",
            [
                'body' => ['url' => $url],
            ]
        );

        return new MediaUrl(json_decode($response->getContent(), true)['url']);
    }
}