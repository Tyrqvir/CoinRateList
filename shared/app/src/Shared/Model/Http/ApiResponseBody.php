<?php

declare(strict_types=1);

namespace App\Shared\Model\Http;

use JsonSerializable;

class ApiResponseBody implements JsonSerializable
{

    protected ApiResponseStatus $status;

    protected array $data;

    public function __construct(array $data = [], int $statusCode = ApiResponseStatus::STATUS_OK, ?string $statusMessage = null)
    {
        $this->data = $data;
        $this->status = new ApiResponseStatus($statusCode, $statusMessage);
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status,
            'data' => $this->data,
        ];
    }
}
