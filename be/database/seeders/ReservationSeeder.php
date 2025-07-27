<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder {

    public function run(): void {
        // The restaurant is closed on Sundays
        // Open Mon–Sat from 11:00 to 23:00
        // Each reservation lasts 2.5 hours

        $users = User::where('role', 'guest')->get();
        $tables = Table::all();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $date = $startOfMonth->copy();

        while ($date->lte($endOfMonth)) {
            // skip Sundays
            if ($date->isSunday()) {
                $date->addDay();
                continue;
            }

            // generate available time slots (every 30 min from 11:00 to 20:00)
            $timeSlots = [];
            for ($hour = 11; $hour <= 20; $hour++) {
                $timeSlots[] = ['hour' => $hour, 'minute' => 0];

                // add 30 only if it is not the last hour (20)
                if ($hour < 20) {
                    $timeSlots[] = ['hour' => $hour, 'minute' => 30];
                }
            }

            foreach ($users as $user) {
                if (rand(0, 1) === 1) {
                    $slot = $timeSlots[array_rand($timeSlots)];

                    $startsAt = $date->copy()->setTime($slot['hour'], $slot['minute']);
                    $endsAt = $startsAt->copy()->addMinutes(150);

                    // ensure reservation doesn't go past 'closing time'
                    $close = $date->copy()->setTime(22, 30);
                    if ($endsAt->gt($close)) {
                        $endsAt = $close;
                    }

                    Reservation::create([
                        'user_id' => $user->id,
                        'table_id' => $tables->random()->id,
                        'starts_at' => $startsAt,
                        'ends_at' => $endsAt,
                        'guests_count' => rand(1, 6),
                        //'phone' => $user->phone,
                        //'last_name' => $user->last_name
                    ]);
                }
            }

            $date->addDay();
        }
    }
}
