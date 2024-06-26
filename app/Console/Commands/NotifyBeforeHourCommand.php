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
        try {
            $oneHourNext = now()->addHour()->second(0);
            $reservations = Reservation::with(['user'])
                ->where('status', Reservation::$approve)
                ->where('notified', false)
                ->where('start_date', '<=', $oneHourNext)
                ->get();

            Log::info($oneHourNext);

            foreach ($reservations as $reservation) {
                Log::info($reservation->id);

                SendNotifications::dispatch($reservation->user->id,
                    'One hour left for reservation number: ' . $reservation->id,
                    Notification::$user,
                    $reservation->id,
                    Notification::$readonly);

                Reservation::where('id', $reservation->id)
                    ->update([
                    'notified' => true,
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
