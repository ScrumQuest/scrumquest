<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbsenceItemRequest;
use App\Models\AbsenceItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenceItemController extends Controller
{
    public function store(StoreAbsenceItemRequest $request, Project $project)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $data = $request->validated();
        AbsenceItem::create($data);

        return redirect(route('sprints.show', [$project, $data['sprint_id'], $data['week_in_sprint']]));
    }

    public function destroy(Request $request, Project $project, AbsenceItem $absenceItem)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $absenceItem->delete();

        return redirect(route('sprints.show', [$project, $request['sprint_id'], $request['week_in_sprint']]));
    }
}
