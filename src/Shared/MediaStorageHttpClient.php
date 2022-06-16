<?php

declare(strict_types=1);

namespace Promorepublic\MediaStorageClient\Shared;

use Promorepublic\MediaStorageClient\ErrorResponse;
use Promorepublic\MediaStorageClient\ExceptionConverter;
use Promorepublic\MediaStorageClient\Shared\Exception\AuthException;
use Promorepublic\MediaStorageClient\Shared\Exception\UploadMediaValidationException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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
     * @throws AuthException
     * @throws UploadMediaValidationException
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

        switch ($response->getStatusCode()) {
            case 500:
                return $this->throwResponseException($response);
            case 200:
                return json_decode($response->getContent())->path;
            default:
                $responseContent = json_decode($response->getContent(false));

                $errorResponse = new ErrorResponse(
                    $responseContent->message,
                    $responseContent->code
                );
                $mediaStorageException = ExceptionConverter::convert($errorResponse);

                if (null !== $mediaStorageException) {
                    throw $mediaStorageException;
                }

                return $this->throwResponseException($response);
        }
    }

        /**
         * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
         * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
         * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
         * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
         */
        private function throwResponseException(ResponseInterface $response): string
    {
        return $response->getContent();
    }
}