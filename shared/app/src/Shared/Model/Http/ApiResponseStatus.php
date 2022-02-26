<?php

declare(strict_types=1);

namespace App\Shared\Model\Http;

use JsonSerializable;

class ApiResponseStatus implements JsonSerializable
{
    public const STATUS_OK = 1;
    public const STATUS_ERROR = 0;
    public const STATUS_UNKNOWN = -1;

    public const STATUS_MESSAGES
        = [
            self::STATUS_OK => 'Ok',
            self::STATUS_ERROR => 'Error',
            self::STATUS_UNKNOWN => 'Unknown',
        ];


    protected int $code;

    protected string $message;

    public function __construct(int $statusCode, ?string $statusMessage = null)
    {
        $this->code = $statusCode;
        $this->message = $statusMessage ?? $this->getStatusMessageByCode($statusCode);
    }

    private function getStatusMessageByCode(int $statusCode): string
    {
        return self::STATUS_MESSAGES[$statusCode] ?? self::STATUS_MESSAGES[self::STATUS_UNKNOWN];
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
}