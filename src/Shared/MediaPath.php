<?php

namespace Promorepublic\MediaStorage\Shared;

final class MediaPath
{
    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }
}