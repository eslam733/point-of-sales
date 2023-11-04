<?php

namespace App\Console\Commands;

use App\Events\SendNotifications;
use App\Models\Notification;
use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyBeforeHourCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-before-hour-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oneHourNext = now()->addHour()->second(0);
        Log::error($oneHourNext);
        $reservations = Reservation::with(['user'])
            ->where('status', Reservation::$approve)
            ->where('start_date', $oneHourNext)
            ->get();
        Log::error($oneHourNext);
        foreach ($reservations as $reservation) {
            Log::error($reservation->id);
            SendNotifications::dispatch($reservation->user->id,
                'One hour left for reservation number: ' . $reservation->id,
                Notification::$user,
                $reservation->id,
                Notification::$readonly);
        }
    }
}
