<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ReservationController extends Controller {

    /**
     * @OA\Get(
     *     path="/api/reservations",
     *     summary="List reservations based on user role",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Reservations retrieved successfully"
     *     )
     * )
     */
    public function index() {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Load reservations with related user
            $reservations = Reservation::with('user')->get();

            return $reservations->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'table_id' => $reservation->table_id,
                    'created_at' => $reservation->created_at,
                    'starts_at' => $reservation->starts_at,
                    'ends_at' => $reservation->ends_at,
                    'guests_count' => $reservation->guests_count,
                    'note' => $reservation->note,
                    'first_name' => optional($reservation->user)->first_name ?? $reservation->first_name,
                    'last_name' => optional($reservation->user)->last_name ?? $reservation->last_name,
                    'phone' => optional($reservation->user)->phone ?? $reservation->phone,
                ];
            });
        }

        // Guest logic – own reservations by user_id or phone
        return Reservation::where(function ($query) use ($user) {
            $query->where('user_id', $user->id);
            if (!empty($user->phone)) {
                $query->orWhere('phone', $user->phone);
            }
        })->get();
    }

    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     summary="Create a reservation for the current user",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"starts_at", "guests_count"},
     *             @OA\Property(property="starts_at", type="string", format="date-time", example="2025-08-01T18:00:00"),
     *             @OA\Property(property="guests_count", type="integer", example=2),
     *             @OA\Property(property="note", type="string", example="Window seat")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Reservation created"),
     *     @OA\Response(response=403, description="Only guests can create reservations"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request) {

        $user = Auth::user();

        if ($user->role !== 'guest') {
            return response()->json(['message' => 'Only guests can create their own reservations.'], 403);
        }

        $validated = $request->validate([
            'starts_at' => 'required|date|after:now',
            'guests_count' => 'required|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ]);

        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = $startsAt->copy()->addMinutes(150); // 2.5 hours

        if (! $this->isValidReservationTime($startsAt)) {
            return response()->json(['error' => 'Reservations must be between 11:00 and 20:30 and not on Sundays.'], 400);
        }

        if ($this->hasUserReservationOnDate($startsAt)) {
            return response()->json(['error' => 'You already have a reservation for this day.'], 409);
        }


        try {
            $this->createReservation(
                $validated['guests_count'],
                $startsAt,
                $endsAt,
                $user->id,
                null,
                null,
                null,
                $validated['note'] ?? null
            );

            return response()->json(['message' => 'The reservation was successfully created.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 409);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/reservations/user",
     *     summary="Admin creates reservation for a registered user",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "starts_at", "guests_count"},
     *             @OA\Property(property="user_id", type="integer", example=3),
     *             @OA\Property(property="starts_at", type="string", format="date-time", example="2025-08-01T18:00:00"),
     *             @OA\Property(property="guests_count", type="integer", example=4),
     *             @OA\Property(property="note", type="string", example="Birthday dinner")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Reservation created for user"),
     *     @OA\Response(response=403, description="Only admins can access this"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeForUser(Request $request) {

        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Only admins can create reservations for users.'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'starts_at' => 'required|date|after:now',
            'guests_count' => 'required|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ]);

        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = $startsAt->copy()->addMinutes(150);

        if (! $this->isValidReservationTime($startsAt)) {
            return response()->json(['error' => 'Reservations must be between 11:00 and 20:30 and not on Sundays.'], 400);
        }

        if ($this->hasUserReservationAt($validated['user_id'], $startsAt)) {
            return response()->json(['error' => 'The user already has a reservation at this time.'], 409);
        }

        try {
            $this->createReservation(
                $validated['guests_count'],
                $startsAt,
                $endsAt,
                $validated['user_id'],
                null,
                null,
                null,
                $validated['note'] ?? null
            );

            return response()->json(['message' => 'Reservation for guest has been created.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 409);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/reservations/phone",
     *     summary="Admin creates reservation for an unregistered guest by phone",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone", "last_name", "starts_at", "guests_count"},
     *             @OA\Property(property="phone", type="string", example="+421912345678"),
     *             @OA\Property(property="first_name", type="string", example="Anna"),
     *             @OA\Property(property="last_name", type="string", example="Smith"),
     *             @OA\Property(property="starts_at", type="string", format="date-time", example="2025-08-01T19:00:00"),
     *             @OA\Property(property="guests_count", type="integer", example=2),
     *             @OA\Property(property="note", type="string", example="Allergic to peanuts")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Reservation created for unregistered guest"),
     *     @OA\Response(response=403, description="Only admins can access this"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeByPhone(Request $request) {

        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Only admins can create phone-based reservations.'], 403);
        }

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'starts_at' => 'required|date|after:now',
            'guests_count' => 'required|integer|min:1',
            'note' => 'nullable|string|max:1000',
        ]);

        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = $startsAt->copy()->addMinutes(150);

        if (! $this->isValidReservationTime($startsAt)) {
            return response()->json(['error' => 'Reservations must be between 11:00 and 20:30 and not on Sundays.'], 400);
        }

        if ($this->hasPhoneReservationAt($validated['phone'], $startsAt)) {
            return response()->json(['error' => 'This phone number already has a reservation at this time.'], 409);
        }

        try {
            $this->createReservation(
                $validated['guests_count'],
                $startsAt,
                $endsAt,
                null,
                $validated['phone'],
                $validated['first_name'] ?? null,
                $validated['last_name'],
                $validated['note'] ?? null
            );

            return response()->json(['message' => 'Reservation for unregistered guest has been created.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 409);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/reservations/{id}",
     *     summary="Cancel a reservation (soft delete)",
     *     tags={"Reservations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Reservation ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Reservation cancelled"),
     *     @OA\Response(response=403, description="Only admins can cancel reservations"),
     *     @OA\Response(response=404, description="Reservation not found")
     * )
     */
    public function destroy($id) {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Only admins can cancel reservations.'], 403);
        }

        $reservation = Reservation::findOrFail($id);
        $reservation->delete(); // soft delete

        return response()->json(['message' => 'Reservation cancelled.'], 200);
    }

    /**
     * Attempts to create a reservation by finding an available table for the given guest count and time range.
     * This method runs inside a database transaction and uses `lockForUpdate()` to ensure exclusive access to table data,
     * preventing two concurrent reservations from being assigned the same table.
     */
    private function createReservation(
        int $guests,
        Carbon $startsAt,
        Carbon $endsAt,
        ?int $userId = null,
        ?string $phone = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $note = null
    ) {
        return DB::transaction(function () use ($guests, $startsAt, $endsAt, $userId, $phone, $firstName, $lastName, $note) {
            $table = $this->findAvailableTable($guests, $startsAt, $endsAt);

            if (!$table) {
                throw new \Exception('No available table found.');
            }

            return Reservation::create([
                'user_id'      => $userId,
                'table_id'     => $table->id,
                'starts_at'    => $startsAt,
                'ends_at'      => $endsAt,
                'guests_count' => $guests,
                'note'         => $note,
                'phone'        => $phone,
                'first_name'   => $firstName,
                'last_name'    => $lastName,
            ]);
        });
    }

    /**
     * Find the best available table for the given time and guest count.
     * Returns table ID or null if none are available.
     */
    private function findAvailableTable(int $guestsCount, Carbon $startsAt, Carbon $endsAt): ?Table {
        return Table::where('seats', '>=', $guestsCount)
            ->whereDoesntHave('reservations', function ($query) use ($startsAt, $endsAt) {
                $query->where('starts_at', '<', $endsAt)
                    ->where('ends_at', '>', $startsAt);
            })
            ->orderBy('seats')
            ->lockForUpdate()
            ->first();
    }

    /**
     * Validates if the reservation start time is within the allowed hours and not on Sunday.
     */
    private function isValidReservationTime(Carbon $startsAt): bool {

        $openingTime = $startsAt->copy()->setTime(11, 0);
        $lastStartTime = $startsAt->copy()->setTime(20, 30);

        if ($startsAt->lt($openingTime) || $startsAt->gt($lastStartTime)) {
            return false;
        }

        if ($startsAt->isSunday()) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the guest user already has a reservation on the given date.
     */
    private function hasUserReservationOnDate(Carbon $date): bool {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return false;
        }

        return Reservation::whereDate('starts_at', $date->toDateString())
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Checks if the given user already has a reservation that starts at the specified time.
     *
     * Used by admins to prevent creating duplicate reservations for the same user at the same time.
     */
    private function hasUserReservationAt(int $userId, Carbon $startsAt): bool {
        return Reservation::where('user_id', $userId)
            ->where('starts_at', $startsAt)
            ->exists();
    }

    /**
     * Checks if there is already a reservation with the same phone number at the specified start time.
     *
     * Used to prevent multiple reservations for the same guest (identified by phone) at the same time.
     */
    private function hasPhoneReservationAt(string $phone, Carbon $startsAt): bool
    {
        return Reservation::where('phone', $phone)
            ->where('starts_at', $startsAt)
            ->exists();
    }

}
