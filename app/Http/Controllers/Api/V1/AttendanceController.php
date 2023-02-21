<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Date;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller {
    public function index(Request $request) {
        $request->validate([
            'user_id' => ['int', 'exists:users,id'],
            'date' => ['date_format:Y-m']
        ]);

        $attendances = Attendance::query()->when($request->filled('user_id'), function ($query) use ($request) {
                $query->whereRelation('user', 'id', '=', $request->input('user_id'));
            })->when($request->filled('date'), function ($query) use ($request) {
                $date = Date::make($request->input('date'));
                $query->whereYear('date', $date->year)->whereMonth('date', $date->month);
            })->get();
        return response()->json(['attendances' => $attendances], Response::HTTP_OK);
    }

    public function store(Request $request) {
    }

    public function show($id) {
    }

    public function update(Request $request, $id) {
    }

    public function destroy($id) {
    }
}
