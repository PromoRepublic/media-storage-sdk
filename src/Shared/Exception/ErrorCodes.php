<?php

declare(strict_types=1);

namespace Promorepublic\MediaStorageClient\Shared\Exception;

interface ErrorCodes
{
    public const API_KEY_NOT_PROVIDED = 10;

    public const API_KEY_WRONG = 11;

    public const FIELDS_NOT_PROVIDED = 20;

    public const HTTP_NOT_FOUND = 404;
}