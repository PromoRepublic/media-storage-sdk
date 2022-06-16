<?php
declare(strict_types=1);

namespace Promorepublic\MediaStorageClient;

final class ErrorResponse
{
    public string $message;

    public int $code;

    public function __construct(string $message, int $code) {
        $this->message = $message;
        $this->code = $code;
    }
}