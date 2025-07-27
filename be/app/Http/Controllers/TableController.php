<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\JsonResponse;

class TableController extends Controller {

    /**
     * @OA\Get(
     *     path="/api/tables/seats",
     *     summary="Get list of all possible seat counts (1 to max)",
     *     tags={"Tables"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of available seat count options"
     *     )
     * )
     */
    public function seatOptions(): JsonResponse {
        $seats = Table::query()
            ->select('seats')
            ->distinct()
            ->pluck('seats')
            ->sort()
            ->values();

        if ($seats->isEmpty()) {
            return response()->json([]);
        }

        $min = 1;
        $max = $seats->max();

        // Create full range from min to max (inclusive)
        $fullRange = range($min, $max);

        return response()->json($fullRange);
    }
}
