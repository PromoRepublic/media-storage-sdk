<?php
declare(strict_types=1);

namespace Promorepublic\MediaStorageClient;

use Exception;
use Promorepublic\MediaStorageClient\Shared\Exception\AuthException;
use Promorepublic\MediaStorageClient\Shared\Exception\UploadMediaValidationException;

final class ExceptionConverter
{
    private const FIELDS_NOT_PROVIDED = 4001;

    private const API_KEY_NOT_PROVIDED = 4011;

    private const API_KEY_WRONG = 4012;

    public static function convert(ErrorResponse $response): ?Exception
    {
        switch ($response->code) {
            case self::FIELDS_NOT_PROVIDED:
                $exception = new UploadMediaValidationException($response->message, $response->code);
                break;
            case self::API_KEY_NOT_PROVIDED:
            case self::API_KEY_WRONG:
                $exception = new AuthException($response->message, $response->code);
                break;
            default:
                return null;
        }
        throw $exception;
    }
}