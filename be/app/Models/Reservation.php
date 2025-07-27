<?php

namespace App\Models;

use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'table_id',
        'starts_at',
        'ends_at',
        'guests_count',
        'note',
        'phone',
        'first_name',
        'last_name',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
