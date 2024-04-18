<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'user_id',
        'item_id',
        'price',
        'status',
        'update_at'
    ];

    public static string $approve = 'approve';
    public static string $reject = 'reject';
    public static string $pending = 'pending';
    public static string $closed = 'closed';
    public static string $canceled = 'canceled';

    public function reservationItems() {
        return $this->hasMany(ReservationItem::class, 'reservation_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function item() {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function itemFeatures() {
        return $this->hasMany(ReservationItem::class, 'reservation_id', 'id');
    }

    public function getItemsName()
    {
        $itemsTitle = '';

        foreach ($this->reservationItems as $reservationItem) {
            $itemsTitle .= $reservationItem->featureItem->name . ', ' ;
        }

        return $itemsTitle;
    }
}
