<?php

namespace Promorepublic\MediaStorage\Shared;

final class MediaUrl
{
    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }
}