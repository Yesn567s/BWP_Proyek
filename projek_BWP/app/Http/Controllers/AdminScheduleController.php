<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminScheduleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:ticket_products,product_id',
            'studio_id'    => 'required|exists:studios,studio_id',
            'date'         => 'required|date',
            'start_time'   => 'required', // 18:00
            'duration'     => 'required|integer', // minutes
            'buffer'       => 'required|integer', // minutes
            'close_time'   => 'required', // 23:00
        ]);

        DB::beginTransaction();

        try {
            $start = Carbon::parse($request->date . ' ' . $request->start_time);
            $close = Carbon::parse($request->date . ' ' . $request->close_time);

            while (true) {
                $end = (clone $start)->addMinutes($request->duration);

                if ($end > $close) break;

                DB::table('schedules')->insert([
                    'product_id'     => $request->product_id,
                    'studio_id'      => $request->studio_id,
                    'start_datetime' => $start,
                    'end_datetime'   => $end,
                ]);

                $start = $end->addMinutes($request->buffer);
            }

            DB::commit();

            return response()->json([
                'message' => 'Schedules created successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
