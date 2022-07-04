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

final class MediaStorageClient
{
    private string $uploadEndpoint = 'upload';

    private Client $httpClient;

    private string $baseUrl;

    private const IMAGE_EXTENSIONS = "/(?:\.jp[e]?g|\.png|\.gif)$/i";

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
    public function uploadImage(string $url): string
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
            switch ($clientException->getResponse()->getStatusCode()) {
                case 404:
                    $errorResponse = [
                        'message' => 'Not found!',
                        'code' => $clientException->getResponse()->getStatusCode()
                    ];
                    break;
                default:
                    $errorResponse = json_decode(
                        $clientException->getResponse()->getBody()->getContents(),
                        true);
            }
            throw ExceptionConverter::convert(
                $errorResponse['message'],
                $errorResponse['code'] ?? null
            );
        } catch (ServerException $serverException) {
            throw new MediaStorageStorageClientServerException($serverException->getMessage());
        } catch (\Throwable $unknownException) {
            throw new MediaStorageStorageClientUnknownException($unknownException->getMessage());
        }
    }

    public function findAndUploadImages(array $arrayWithImages): array
    {
        $arrayWithCachedImages = $arrayWithImages;

        $this->uploadImagesRecursive($arrayWithCachedImages);

        return $arrayWithCachedImages;
    }

    private function uploadImagesRecursive(array &$arrayWithImages): void
    {
        foreach ($arrayWithImages as $key => &$value) {
            if (is_array($value)) {
                $this->uploadImagesRecursive($value);
            }

            //the key can be int index, so it should convert that in to a string
            if (is_string($value) && $this->validateValue($value) && $this->validateKey("$key")) {
                $arrayWithImages["media_storage_$key"] = $this->uploadImage($value);
            }
        }
    }
    private function validateValue(string $string): bool
    {
        return 0 === strpos($string, "https") && (preg_match(
                self::IMAGE_EXTENSIONS,
                parse_url($string)['path'])
            );
    }

    private function validateKey(string $key): bool
    {
        return 0 !== strpos($key, "media_storage");
    }
}
