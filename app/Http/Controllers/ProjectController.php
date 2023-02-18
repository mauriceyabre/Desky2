<?php

namespace App\Http\Controllers;

use App\Events\ProjectUpdatedEvent;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectStoreRequest;
use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ProjectController extends Controller {

    public function index() : Response {
        return Inertia::render('App/ProjectsIndex', [
            'projects' => Project::uncompleted()->get(),
            'phases' => Project::$PHASES,
            'members' => User::get(['id','first_name','last_name']),
            'page' => [
                'title' => 'Progetti',
                'isFluid' => true
            ]
        ]);
    }

    public function create() {
        //
    }

    public function fetch(Request $request, string $list = 'active', string $user_id = '') {
        if ($request->isNotFilled('search')) {
            return match ($list) {
                'kanbanized' => Project::kanbanized()->get()->toJson(),
                'completed' => Project::completed()->get()->toJson(),
                'archived' => Project::onlyTrashed()->get()->toJson(),
                'all' => Project::withTrashed()->get()->toJson(),
                default => Project::uncompleted()->get()->toJson()
            };
        } else {
            $string = (string) $request->input('search');
            $searchValues = preg_split('/\s+/', $string, -1, PREG_SPLIT_NO_EMPTY);

            $projects = Project::query()
                ->with(['customer' => function($q) {
                    $q->select(['id', 'name']);
                }, 'members' => function($q) {
                      $q->without(['details', 'projects', 'attendances', 'latestAttendance'])->select(['users.id']);
                }])
                ->without(['creator'])
                ->where(function ($q) use ($searchValues) {
                    foreach ($searchValues as $value) {
                        $q->orWhere('name', 'LIKE', "%$value%")->orWhereHas('customer', function ($q) use ($value) {
                            $q->where('name', 'LIKE', "%$value%");
                        });
                    }
                    $q->where('phase', '!=', 'deal');
                })
                ->get();

            return $projects->sortBy('name')->sortByDesc('started_at')->sortByDesc(function ($i) use ($searchValues, $user_id) {
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

                    if(!empty($user_id) && collect($i['members'])->contains('id', $user_id)) {
                        $weight += 10;
                    }
                }

                return $weight;
            })->take(5)->toJson();
        }
    }

    public function phases() {
        return Project::$PHASES;
    }

    public function get($id) {
        $project = Project::where('id', $id)->first();
        return response()->json($project);
    }

    /**
     * @throws Throwable
     */
    public function quickStore(ProjectStoreRequest $request) {
        $validated = $request->validated();

        $project = new Project($validated);
        $project->creator()->associate(Auth::user());
        $project->saveOrFail();

        broadcast(new ProjectUpdatedEvent())->toOthers();

        return back();
    }

    public function store(Request $request) {
        dump($request);
        //
    }

    public function show(Project $project) {
        //
    }

    public function edit(Project $project) {
        //
    }

    public function archived() {
        return Inertia::render('App/ProjectsArchived', [
            'projects' => fn() => Project::onlyTrashed()->orderBy('deleted_at', 'DESC')->paginate(10)->withQueryString(),
            'page' => [
                'title' => 'Progetti Archiviati'
            ]
        ]);
    }

    public function completed() {
        return Inertia::render('App/ProjectsCompleted', [
            'projects' => fn() => Project::completed()
                ->orderBy('completed_at', 'DESC')
                ->paginate(10, ['id', 'name', 'phase', 'customer_id', 'completed_at', 'deadline'])
                ->withQueryString(),
            'page' => [
                'title' => 'Progetti Completati'
            ]
        ]);
    }

    /**
     * @throws Throwable
     */
    public function deliver($id) {
        $last = Project::where('phase', '=', 'delivered')->orderBy('index', 'DESC')->first();
        $newIndex = 0;
        if ($last) {
            $newIndex = $last->index + 1;
        }

        Project::findOrFail($id)->updateOrFail(['phase' => 'delivered', 'index' => $newIndex, 'delivered_at' => now()]);
        broadcast(new ProjectUpdatedEvent())->toOthers();

        return back();
    }

    /**
     * @throws Throwable
     */
    public function archive($id) {
        Project::findOrFail($id)->deleteOrFail();
        broadcast(new ProjectUpdatedEvent())->toOthers();

        return back()->with('toast', ['message' => 'Progetto Archiviato', 'type' => 'warning']);
    }

    /**
     * @throws Throwable
     */
    public function complete($id) {
        Project::findOrFail($id)->updateOrFail([
            'phase' => 'completed',
            'completed_at' => now()
        ]);

        broadcast(new ProjectUpdatedEvent())->toOthers();
        return back()->with('toast', ['message' => 'Progetto Completato', 'type' => 'success']);
    }

    public function destroy($id) {
        Project::withTrashed()->findOrFail($id)->forceDelete();
        broadcast(new ProjectUpdatedEvent())->toOthers();

        return back()->with('toast', ['message' => 'Progetto Eliminato', 'type' => 'danger']);
    }

    public function restore($id) {
        Project::onlyTrashed()->findOrFail($id)->restore();
        broadcast(new ProjectUpdatedEvent())->toOthers();

        return back()->with('toast', ['message' => 'Progetto Ripristinato']);
    }

    /**
     * @throws Throwable
     */
    public function update(ProjectRequest $request, $id) {

        // Verifica se il Project esiste o fallisce
        $project = Project::findOrFail($id);
        $newData = $request->except(['customer_id', 'new_customer']);

        // Get Customer ID
        if ($request->has('customer_id')) {
            $customer = Customer::find($request->input('customer_id'));
            if ($customer) {
                $newData['customer_id'] = $customer->id;
            } else if ($request->input('new_customer')) {
                $customer = Customer::create([
                    'name' => $request->input('new_customer'),
                    'created_by' => Auth::id()
                ]);
                $newData['customer_id'] = $customer->id;
            } else {
                $newData['customer_id'] = null;
            }
        }

        // Aggiorna Progetti
        $project->updateOrFail($newData);

        // Membri
        if ($request->has('members'))
            $project->members()->sync($request->input('members'));

        broadcast(new ProjectUpdatedEvent())->toOthers();

        return back();
    }

    /**
     * @throws Throwable
     */
    public function updateIndexes(Request $request) : RedirectResponse {

        $toItems = collect($request->input('toItems'));
        $fromItems = collect($request->input('fromItems'));
        $itemId = $request->input('itemId');
        $fromPhaseId = $request->input('fromPhaseId');
        $toPhaseId = $request->input('toPhaseId');

        $project = Project::findOrFail($itemId);

        if ($fromPhaseId) {
            if ($fromPhaseId == 'deal' && $toPhaseId == 'new') $project->started_at = now()->toImmutable();
            if ($toPhaseId == 'deal' && $project->started_at) $project->started_at = null;
            if ($toPhaseId !== 'delivered') {
                if ($project->delivered_at) {
                    $project->delivered_at = null;
                }
            } else {
                $project->delivered_at = now();
            }
            $project->phase = $toPhaseId;
        }

        $project->saveOrFail();

        $newIndexes = $toItems;

        if ($fromPhaseId) {
            $newIndexes->merge($fromItems);
        }

        Project::upsert($newIndexes->toArray(), 'id');

        broadcast(new ProjectUpdatedEvent())->toOthers();

        return back();
    }
}
