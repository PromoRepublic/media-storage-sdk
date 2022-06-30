<?php

declare(strict_types=1);

namespace Promorepublic\MediaStorageClient;

use Exception;
use Promorepublic\MediaStorageClient\Shared\Exception\ErrorCodes;
use Promorepublic\MediaStorageClient\Shared\Exception\MediaStorageClientSocialNetworkUndefinedException;
use Promorepublic\MediaStorageClient\Shared\Exception\MediaStorageStorageClientAuthException;
use Promorepublic\MediaStorageClient\Shared\Exception\MediaStorageStorageClientUnknownException;
use Promorepublic\MediaStorageClient\Shared\Exception\MediaStorageClientUploadMediaValidationExceptionStorage;

final class ExceptionConverter
{
    private const FIELDS_NOT_PROVIDED = 20;

    private const API_KEY_NOT_PROVIDED = 10;

    private const API_KEY_WRONG = 11;

    public static function convert(string $message, ?int $code): Exception
    {
        switch ($code) {
            case ErrorCodes::FIELDS_NOT_PROVIDED:
                $exception = new MediaStorageClientUploadMediaValidationExceptionStorage($message, $code);
                break;
            case ErrorCodes::API_KEY_NOT_PROVIDED:
            case ErrorCodes::API_KEY_WRONG:
                $exception = new MediaStorageStorageClientAuthException($message, $code);
                break;
            case ErrorCodes::SOCIAL_NETWORK_NOT_RECOGNIZED:
                $exception = new MediaStorageClientSocialNetworkUndefinedException($message, $code);
                break;
            default:
                $exception = new MediaStorageStorageClientUnknownException($message);
                break;
        }
        return $exception;
    }
}
