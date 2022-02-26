<?php

declare(strict_types=1);

namespace App\Shared\Message\Query;

use App\Shared\Message\Contracts\QueryInterface;
use App\Shared\Message\Contracts\RateQueryInterface;
use Symfony\Component\Validator\Constraints;

abstract class AbstractRateQuery implements QueryInterface, RateQueryInterface
{

    /**
     * @Constraints\NotBlank()
     */
    private string $coin;
    /**
     * @Constraints\NotBlank()
     */
    private string $currency;

    public function __construct(string $coin, string $currency)
    {
        $this->coin = $coin;
        $this->currency = $currency;
    }

    public function getCoin(): string
    {
        return $this->coin;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

}