<?php

namespace App\Http\Controllers;

use App\Events\SendNotifications;
use App\Models\FeatureItem;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\ReservationItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

        $validator = Validator::make($request->all(), [
            'startDate' => ['required', 'date_format:' . $this->dayFormat . ' ' . $this->timeFormat],
            'endDate' => ['required', 'date_format:' . $this->dayFormat . ' ' . $this->timeFormat],
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'features_item' => ['required', 'array', 'exists:feature_items,id'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $data = $request->all();

        $status = $this->checkTimeAvailability(
            $data['startDate'],
            $data['endDate'],
            $data['item_id'],
            $data['features_item']
        );

        if (!$status) {
            return $this->errorResponse('Sorry, this time not available', [], 400);
        }

        $price = FeatureItem
            ::select('price')
            ->whereIn('id', $data['features_item'])
            ->sum(DB::raw('price'));

        $featuresItemsIds = FeatureItem
        ::whereIn('id', $data['features_item'])
        ->pluck('id');

        $user = auth()->user();

        $closed = $user->isAdmin();

        $reservation = Reservation::create([
            'start_date' => $data['startDate'],
            'end_date' => $data['endDate'],
            'price' => $price,
            'user_id' => $user->id,
            'item_id' => $data['item_id'],
            'status' => $closed ? Reservation::$closed : Reservation::$pending,
        ]);

        $itemsToInsert = [];

        foreach ($featuresItemsIds as $featureItemId) {
            $itemsToInsert[] = [
                'reservation_id' => $reservation->id,
                'feature_item_id' => $featureItemId,
            ];
        }

        ReservationItem::insert($itemsToInsert);

        if (!$closed) {
            $item = Item::where('id', $data['item_id'])->first();
            SendNotifications::dispatch(auth()->id(),
                'new reservation(' . $reservation->id . ') at ' . $data['startDate'] . ', item: ' . $item->name);
        }

        return $this->successResponse($closed ? 'Time has been closed' : 'reservation created', $reservation, 200);

    }

    public function getDatesForItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => ['required', 'date_format:' . $this->dayFormat],
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'features_item' => ['required', 'array', 'exists:feature_items,id'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $data = $request->all();
        $avaliableTimes = [];

        $day = $request->get('day'); // string date

        $time = Carbon::createFromTimeString($this->openTime);

        while ($time->lt($this->closeTime->copy()->subHour()) || $time->eq($this->closeTime->copy()->subHour())) {

            $start = $time->copy();
            $end = $start->copy()->addHour();

            $status = $this->checkTimeAvailability(
                $day . ' ' . $start->format($this->timeFormat),
                $day . ' ' . $end->format($this->timeFormat),
                $data['item_id'],
                $data['features_item'],
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

    private function checkTimeAvailability($startTime, $endTime, $itemId, $featuresItemsIds)
    {
        $startDate = Carbon::createFromTimeString($startTime);
        $endDate = Carbon::createFromTimeString($endTime);

        $status = $startDate->isAfter(Carbon::now());

        while ($status && ($startDate->isBefore($endDate))) {

            $temp = $startDate->format($this->dayFormat . ' ' . $this->timeFormat);

            $days = DB::table('reservations')
                ->join('reservation_items', 'reservations.id', '=', 'reservation_items.reservation_id')
                ->where('item_id', $itemId)
                ->whereIn('feature_item_id', $featuresItemsIds)
                ->where('start_date', '<=', $temp)
                ->where('end_date', '>', $temp)
                ->whereIn('status', [Reservation::$pending, Reservation::$approve, Reservation::$closed])
                ->first();

            if (!empty($days)) {
                $status = !$status;
                break;
            }

            $startDate->addHour();
        }
        return $status;
    }

    public function getReservation(Request $request)
    {

        $user = auth()->user();

        if ($user->isAdmin()) {
            $reservation = Reservation
                ::with('user')
                ->with('item')
                ->with('reservationItems.featureItem')
                ->get();
        } else {
            $reservation = Reservation
                ::with('user')
                ->with('item')
                ->with('reservationItems.featureItem')
                ->where('user_id', $user->id)
                ->get();
        }

        return $this->successResponse('Reservation', $reservation, 200);
    }

    public function changeStatus(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:pending,reject,approve'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 400);
        }

        $data = $request->all();

        $reservation = Reservation::with(['user', 'item', 'reservationItems' => function ($query) {
            $query->with(['featureItem']);
        }])->where('id', $id)->first();

        if (empty($reservation)) {
            return $this->errorResponse('Reservation not found', $validator->errors(), 404);
        }

        $reservation->update([
            'status' => $request->get('status'),
        ]);

        $itemsTitle = '';

        foreach ($reservation->reservationItems as $reservationItem) {
            $itemsTitle .= $reservationItem->featureItem->name . ', ' ;
        }

        $mailData = [
            'status' => $data['status'],
            'items' => $itemsTitle,
            'day' => explode(' ', $reservation->start_date)[0],
            'startTime' => explode(' ', $reservation->start_date)[1],
            'endTime' => explode(' ', $reservation->end_date)[1],
        ];

        SendNotifications::dispatch($reservation->user->id, 'Your reservation ' . $reservation->id . ' has been ' . $data['status'], Notification::$user);

        Mail::send('reservations.reservation_status', $mailData, function ($message) use ($reservation) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($reservation->user->email);
            $message->subject('Reservation Status');
        });

        return $this->successResponse('Reservation has been updated', [], 200);
    }
}
