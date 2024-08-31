<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\SmsTrait;

class SendScheduledSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-scheduled-s-m-s';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled sms to all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $today = Carbon::today()->toDateString();
        $messages = $this->getMessagesForToday($today);

        $users = User::all(); // Fetch users to send messages

        foreach ($users as $user) {
            foreach ($messages as $message) {
                $this->sendSms($user->contact, $message);
            }
        }

        $this->info('Scheduled SMS messages sent successfully.');
    }

    protected function getMessagesForToday($today)
    {
        $messages = [];
        $specificDates = [
            Carbon::create(Carbon::now()->year, 12, 25)->toDateString() => 'Affum Family wishes you a Merry Christmas!',
            Carbon::create(Carbon::now()->year, 1, 1)->toDateString() => 'Affum Family wishes you a Happy New Year!',
        ];

        $easter = $this->calculateEaster(Carbon::now()->year);
        $christianHolidays = [
            $easter->toDateString() => 'Affum Family wishes you a Happy Easter!',
            $easter->copy()->subDays(2)->toDateString() => 'Affum Family wishes you a Good Friday Blessings!',
        ];

        if (array_key_exists($today, $specificDates)) {
            $messages[] = $specificDates[$today];
        }

        if (array_key_exists($today, $christianHolidays)) {
            $messages[] = $christianHolidays[$today];
        }

        return $messages;
    }

    protected function calculateEaster($year)
    {
        $a = $year % 19;
        $b = floor($year / 100);
        $c = $year % 100;
        $d = floor($b / 4);
        $e = $b % 4;
        $f = floor(($b + 8) / 25);
        $g = floor(($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = floor($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = floor(($a + 11 * $h + 22 * $l) / 451);
        $month = floor(($h + $l - 7 * $m + 114) / 31);
        $day = (($h + $l - 7 * $m + 114) % 31) + 1;

        return Carbon::create($year, $month, $day);
    }
}
