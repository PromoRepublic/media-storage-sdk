<?php

declare(strict_types=1);

namespace Promorepublic\MediaStorageClient\Shared;

final class MediaStorageHttpClientConfigurationBag
{
    public string $apiKey;

    public string $mediaStorageUrl;

    public function __construct(string $apiKey, string $mediaStorageUrl = "0.0.0.0:8000")
    {
        $this->apiKey = $apiKey;
        $this->mediaStorageUrl = $mediaStorageUrl;
    }
}