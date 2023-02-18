<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Inertia\Inertia;

class ProfileController extends Controller {
    public function show() {
        return redirect()->route('profile.overview');
    }

    public function overview() {
        return $this->goTo();
    }

    public function timesheet(Request $request) {
        $date = Date::today();
        if ($request->query('date')) {
            $date = Date::make($request->query('date'));
        }

        $attendances = Attendance::query()
            ->whereRelation('user', 'id', '=', auth()->id())
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->with('projects', function ($query) {
                $query->with('customer:id,name')->without(['members', 'creator']);
            })->get();

        return $this->goTo('timesheet', ['attendances' => $attendances]);
    }

    public function settings() {
        return $this->goTo('settings');
    }

    public function goTo(string $tab = 'overview', ?Array $extraData = null) {
        return Inertia::render('App/Members/MemberShow', [
            'user' => fn() => auth()->user()->load('address'),
            'tab' => fn() => $tab,
            'data' => fn() => $extraData,
            'is_auth' => fn() => true,
            'page' => fn() => [
                'title' => auth()->user()->first_name . ' ' . auth()->user()->last_name
            ]
        ]);
    }
}
