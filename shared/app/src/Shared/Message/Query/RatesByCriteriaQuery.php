<?php

declare(strict_types=1);

namespace App\Shared\Message\Query;

use App\Shared\Message\Contracts\QueryInterface;
use Symfony\Component\Validator\Constraints;

class RatesByCriteriaQuery implements QueryInterface
{
    /**
     * @Constraints\NotBlank()
     */
    private string $coin;
    /**
     * @Constraints\NotBlank()
     */
    private string $currency;

    /**
     * @Constraints\NotBlank()
     * @Constraints\Type("integer")
     */
    private int $start;

    /**
     * @Constraints\NotBlank()
     * @Constraints\Type("integer")
     */
    private int $end;

    public function __construct(string $coin, string $currency, int $start, int $end)
    {
        $this->coin = $coin;
        $this->currency = $currency;
        $this->start = $start;
        $this->end = $end;
    }

    public function getCoin(): string
    {
        return $this->coin;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getEnd(): int
    {
        return $this->end;
    }

}