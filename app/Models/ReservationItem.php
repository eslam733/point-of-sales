<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationItem extends Model
{
    use HasFactory;

    public function featureItem() {
        return $this->belongsTo(FeatureItem::class, 'feature_item_id', 'id');
    }
}
