<?php

namespace Honeybadger\HoneybadgerLaravel\Commands;

use Honeybadger\Contracts\SyncCheckIns;
use Illuminate\Console\Command;

class HoneybadgerCheckInsSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'honeybadger:checkins:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize check-ins to Honeybadger';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SyncCheckIns $checkinsManager)
    {
        $localCheckIns = config('honeybadger.checkins', []);
        $result = $checkinsManager->sync($localCheckIns);
        $this->info('Check-ins were synchronized with Honeybadger.');
        $this->table(['Id', 'Name', 'Slug', 'Schedule Type', 'Report Period', 'Cron Schedule', 'Cron Timezone', 'Grace Period', 'Status'], array_map(function ($checkin) {
            return [
                $checkin->id,
                $checkin->name,
                $checkin->slug,
                $checkin->scheduleType,
                $checkin->reportPeriod,
                $checkin->cronSchedule,
                $checkin->cronTimezone,
                $checkin->gracePeriod,
                $checkin->isDeleted() ? '❌ Removed' : '✅ Synchronized',
            ];
        }, $result));
    }
}
