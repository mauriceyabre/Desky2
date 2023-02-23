<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller {
    public function index(Request $request) {
        dd($request->all());
    }

    public function dropdown(Request $request) {
        $request->validate([
            'search' => ['required', 'sometimes', 'string'],
            'user_id' => ['required', 'exists:users,id']
        ]);

        $search = $request->input('search');
        $user_id = $request->input('user_id');
        $searchValues = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        $projects = Project::query()
            ->active()
            ->with(['customer:id,name', 'members:id'])
            ->without(['creator'])->where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
                $q->orWhere('name', 'LIKE', "%$value%")->orWhereHas('customer', function ($q) use ($value) {
                    $q->where('name', 'LIKE', "%$value%");
                });
            }
        })->get();

        $projects = $projects->sortBy('name')->sortByDesc('started_at')->sortByDesc(function ($i) use ($searchValues, $user_id) {
            $weight = 0;
            foreach ($searchValues as $term) {
                $term = strtolower($term);
                $name = preg_replace('/[^\w\s]/', '', strtolower($i['name']));
                if (str_contains($name, $term)) {
                    $weight += 1;
                }

                $customerName = preg_replace('/[^\w\s]/', '', strtolower($i['customer']['name']));
                if (!empty($i['customer']) && str_contains($customerName, $term)) {
                    $weight += 1;
                }

                if (!empty($user_id) && collect($i['members'])->contains('id', $user_id)) {
                    $weight += 10;
                }
            }

            return $weight;
        })->take(5);

        return response()->json(['projects' => $projects]);
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
