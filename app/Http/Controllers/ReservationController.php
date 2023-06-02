<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    private $dayFormat;
    private $timeFormat;
    private $openTime;
    private $closeTime;

    public function __construct()
    {
        $this->openTime = Carbon::createFromTimeString('10:00:00');
        $this->closeTime = Carbon::createFromTimeString('23:00:00');
        $this->dayFormat = 'Y-m-d';
        $this->timeFormat = 'H:i:s';
    }

    public function store(Request $request)
    {
        $currentDateTime = Carbon::now();
        $currentDay = $currentDateTime->format($this->dayFormat);
        
        return $this->successResponse('reservation created', $currentDay, 200);

    }

    public function getDatesForItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => ['required', 'date_format:' . $this->dayFormat],
            'item_id' => ['required', 'integer', 'exists:items,id'],
        ]);
    
        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $avaliableTimes = [];

        $day = $request->get('day'); // string date

        $time = Carbon::createFromTimeString($this->openTime);

        while ($time->lt($this->closeTime->copy()->subHour()) || $time->eq($this->closeTime->copy()->subHour())) {
            
            $start = $time->copy();
            $end = $start->copy()->addHour();
            
            $status = $this->checkTimeAvailability(
                $day,
                $start->format($this->timeFormat),
                $end->format($this->timeFormat),
            );

            array_push($avaliableTimes, array(
                "start" => $start->format($this->timeFormat),
                "end" => $end->format($this->timeFormat),
                "status" => $status,
            ));

            $time->addHours(1);
        }

        return $this->successResponse('Avalible dates', $avaliableTimes, 200);
    }

    private function checkTimeAvailability($day, $startTime, $endTime)
    {
        $startDate = Carbon::createFromTimeString($day . ' ' . $startTime);
        $endDate = Carbon::createFromTimeString($day . ' ' . $endTime);
        
        $status = $startDate->isAfter(Carbon::now());

        while ($status && ($startDate->isBefore($endDate) || $startDate->equalTo($endDate))) {

            $temp = $startDate->format($this->dayFormat . ' ' . $this->timeFormat);
            
            $days = DB::table('reservations')
            ->where('start_date', '<=', $temp)
            ->where('end_date', '>', $temp)
            ->first();

            if (!empty($days)) {
                $status = !$status;
                break;
            }

            $startDate->addHour();
        }
        return $status;
    }
}
