<?php

declare(strict_types=1);

namespace App\Shared\Message\Query;

use Symfony\Component\Validator\Constraints;

final class DailyRatesRateQuery extends AbstractRateQuery
{
    /**
     * @Constraints\NotBlank()
     * @Constraints\Type("string")
     */
    private string $days;

    /**
     * @Constraints\NotBlank()
     * @Constraints\Type("string")
     */
    private string $interval;

    public function __construct(string $coin, string $currency, string $days = 'max', string $interval = 'daily')
    {
        parent::__construct($coin, $currency);
        $this->days = $days;
        $this->interval = $interval;
    }


    public function getDays(): string
    {
        return $this->days;
    }


    public function getInterval(): string
    {
        return $this->interval;
    }

}