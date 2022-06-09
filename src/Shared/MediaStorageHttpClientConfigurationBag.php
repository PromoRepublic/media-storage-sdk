<?php

namespace Promorepublic\MediaStorage\Shared;

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