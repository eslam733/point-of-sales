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
    ];

    // types of notification
    static public $all = 'all';
    static public $user = 'user';
    static public $admin = 'admin';


    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
