<?php

namespace LeadingSystems\ContaoSchedulerBundle\Cronjob;

use LeadingSystems\ContaoSchedulerBundle\Scheduler\SchedulerDispatcher;

class CronDispatcher
{
    private SchedulerDispatcher $schedulerDispatcher;

    public function __construct(SchedulerDispatcher $schedulerDispatcher)
    {

        $this->schedulerDispatcher = $schedulerDispatcher;
    }

    public function dispatchScheduler()
    {
        $this->schedulerDispatcher->dispatch();
    }
}