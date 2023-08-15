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
    ];

    public function reservationItems() {
        return $this->hasMany(ReservationItem::class, 'reservation_id', 'id');
    }
}
