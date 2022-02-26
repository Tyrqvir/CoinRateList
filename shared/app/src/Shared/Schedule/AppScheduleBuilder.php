<?php

declare(strict_types=1);

namespace App\Shared\Schedule;

use Zenstruck\ScheduleBundle\Schedule;
use Zenstruck\ScheduleBundle\Schedule\ScheduleBuilder;

class AppScheduleBuilder implements ScheduleBuilder
{
    public function buildSchedule(Schedule $schedule): void
    {
        $schedule
            ->timezone('UTC')
            ->environments('prod', 'dev')
        ;

        $schedule->addCommand('schedule:rate:hourly-update')
            ->description('Update rates every hour')
            ->hourly()
        ;
    }
}
