<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'message',
        'type',
        'actions',
        'reservation_id'
    ];

    // types of notification
    static public $all = 'all';
    static public $user = 'user';
    static public $admin = 'admin';

    static $reservation = 'reservation';
    static $readonly = 'readonly';


    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reservation() {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }
}
