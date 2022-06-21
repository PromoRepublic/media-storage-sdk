<?php

declare(strict_types=1);

namespace Promorepublic\MediaStorageClient;

use Exception;
use Promorepublic\MediaStorageClient\Shared\Exception\MediaStorageClientAuthException;
use Promorepublic\MediaStorageClient\Shared\Exception\MediaStorageClientUnknownException;
use Promorepublic\MediaStorageClient\Shared\Exception\MediaStorageClientUploadMediaValidationException;

final class ExceptionConverter
{
    private const FIELDS_NOT_PROVIDED = 20;

    private const API_KEY_NOT_PROVIDED = 10;

    private const API_KEY_WRONG = 11;

    public static function convert(string $message, ?int $code): Exception
    {
        switch ($code) {
            case self::FIELDS_NOT_PROVIDED:
                $exception = new MediaStorageClientUploadMediaValidationException($message, $code);
                break;
            case self::API_KEY_NOT_PROVIDED:
            case self::API_KEY_WRONG:
                $exception = new MediaStorageClientAuthException($message, $code);
                break;
            default:
                $exception = new MediaStorageClientUnknownException($message);
                break;
        }
        return $exception;
    }
}
