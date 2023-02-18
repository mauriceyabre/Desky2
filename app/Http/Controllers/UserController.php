<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserUpdateEmailRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Throwable;

class UserController extends Controller {
    public function index() {
        return Inertia::render('App/Members/MembersIndex', [
            'members' => User::with('todayAttendance', 'address')
                ->get(['id',
                       'first_name',
                       'last_name',
                       'email',
                       'role_key',
                       'phone',
                       'last_login',
                       'birthday',
                       'job',
                ]),
            'counts' => [
                'active' => User::all()->count(),
                'archived' => User::onlyTrashed()->count()
            ],
            'page' => [
                'title' => 'Membri Attivi'
            ]
        ]);
    }

    public function archived() {
        return Inertia::render('App/Members/MembersIndex', [
            'members' => User::onlyTrashed()->get(['id', 'first_name', 'last_name', 'email', 'role_key', 'deleted_at']),
            'counts' => [
                'active' => User::all()->count(),
                'archived' => User::onlyTrashed()->count()
            ],
            'page' => [
                'title' => 'Membri Archiviati'
            ]
        ]);
    }

    public function overview(int $id) {
        return $this->goTo($id);
    }

    public function timesheet(Request $request, int $id) {
        $date = Date::today();
        if ($request->query('date')) {
            $date = Date::make($request->query('date'));
        }

        $attendances = Attendance::query()
            ->whereRelation('user', 'id', '=', $id)
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->with('projects', function ($query) {
                $query->with('customer:id,name')->without(['members', 'creator']);
            })->get();

        return $this->goTo($id, 'timesheet', ['attendances' => $attendances]);
    }

    public function settings(int $id) {
        return $this->goTo($id, 'settings');
    }

    public function goTo(int $id, string $tab = 'overview', ?array $extraData = null) {
        $user = User::with('address')->withTrashed()->findOrFail($id);
        return Inertia::render('App/Members/MemberShow', [
            'user' => fn() => $user,
            'tab' => fn() => $tab,
            'data' => fn() => $extraData,
            'is_auth' => fn() => true,
            'page' => fn() => [
                'title' => $user->first_name . ' ' . $user->last_name
            ]
        ]);
    }

    public function store(StoreUserRequest $request) {
        User::create($request->validated())
            ->address()
            ->create(['country_code' => $request->input('country_code')]);

        return back()->with('toast', ['type' => 'success', 'message' => 'È stato creato un nuovo utente.']);
    }

    public function update(UpdateUserRequest $request, int $id) {
        try {
            $user = User::findOrFail($id);

            if (!empty($request->except('address'))) {
                $user->updateOrFail($request->except('address'));
            }

            if ($request->filled('address')) {
                $address = $request->input('address');
                unset($address['id']);
                $addressId = $request->input('address')['id'];

                if ($addressId) {
                    $user->address()->update($address);
                }
            }
            return back()->with('toast', ['type' => 'success', 'message' => 'Profilo aggiornato.']);
        } catch (Throwable $e) {
            return back()->withErrors($e);
        }
    }

    public function updatePassword(UserUpdatePasswordRequest $request, int $id) {
        $user = User::findOrFail($id);
        if (!Hash::check($request->get('current_password'), $user->password)) {
            // WRONG CURRENT PASSOWORD
            return back()->withErrors(['current_password' => 'The provided password does not match our records.']);
        }
        $user->forceFill([
            'password' => Hash::make($request->get('password')),
        ])->saveOrFail();

        return back()->with('toast', ['message' => 'Password Aggiornata']);
    }

    public function updateEmail(UserUpdateEmailRequest $request, User $user) {
        $user->updateOrFail(['email' => $request->get('email'), 'email_verified_at' => null]);
        return back()->with('toast', ['message' => 'Indirizzo Email Aggiornato']);
    }

    public function restore(Request $request, int $id) {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return back()->with('toast', ['type' => 'success', 'message' => 'Utente Ripristinato']);
    }

    public function archive(int $id) {
        $user = User::findOrFail($id);
        if ($user->is_admin) {
            return back()->with('toast', ['type' => 'danger', 'message' => 'Non puoi archiviare questo utente.']);
        }
        $user->delete();
        return back()->with('toast', ['type' => 'success', 'message' => 'Utente Archiviato.']);
    }

    public function destroy(Request $request, int $id) {
        $user = User::onlyTrashed()->findOrFail($id);
        if ($user->is_admin) {
            return back()->with('toast', ['type' => 'danger', 'message' => 'Non puoi eliminare questo utente.']);
        }
        $user->forceDelete();

        if ($request->filled('action') && $request->input('action') === 'redirect') {
            return to_route('members.archived')->with('toast', ['type' => 'success', 'message' => 'Utente Eliminato.']);
        } else {
            return back()->with('toast', ['type' => 'success', 'message' => 'Utente Eliminato.']);
        }
    }
}
