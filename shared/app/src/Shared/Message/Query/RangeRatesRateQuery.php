<?php

declare(strict_types=1);

namespace App\Shared\Message\Query;

use Symfony\Component\Validator\Constraints;

final class RangeRatesRateQuery extends AbstractRateQuery
{
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
        parent::__construct($coin, $currency);
        $this->start = $start;
        $this->end = $end;
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