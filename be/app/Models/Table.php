<?php

namespace App\Models;

use Database\Factories\TableFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model {
    /** @use HasFactory<TableFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function ($table) {
            $table->update([
                'name' => 'Table ' . $table->id,
            ]);
        });
    }

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
}
