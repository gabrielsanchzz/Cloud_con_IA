<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Requirement;
use App\Http\Requests\StoreRequirementRequest;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    public function create(Request $request, Project $project)
    {
        $type = $request->query('type');

        if (in_array($type, ['number', 'text', 'boolean'], true)) {
            return view('requirements.create', [
                'project' => $project,
                'step' => 2,
                'type' => $type,
            ]);
        }

        return view('requirements.create', [
            'project' => $project,
            'step' => 1,
        ]);
    }

    public function stepTwo(Request $request, Project $project)
    {
        $validated = $request->validate([
            'type' => 'required|in:number,text,boolean',
        ]);

        return redirect()->route('requirements.create', [
            'project' => $project->id,
            'type' => $validated['type'],
        ]);
    }

    public function store(
        StoreRequirementRequest $request,
        Project $project
    ) {
        $project->requirements()->create($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('success', '¡Requisito añadido correctamente!');
    }

    // Mostrar formulario de edición
    public function edit(Project $project, Requirement $requirement)
    {
        return view('requirements.edit', compact('project', 'requirement'));
    }

    // Guardar los cambios
    public function update(
        Request $request,
        Project $project,
        Requirement $requirement
    ) {
        $operators = match ($request->input('type')) {
            'number' => ['=', '!=', '>', '>=', '<', '<='],
            'text' => ['=', '!=', 'contains'],
            'boolean' => ['=', '!='],
            default => [],
        };

        $validated = $request->validate([
            'requirement_key' => 'required|string|max:80|unique:requirements,requirement_key,' .
                $requirement->id . ',id,project_id,' . $project->id,
            'type' => 'required|in:number,text,boolean',
            'operator' => ['required', 'in:' . implode(',', $operators)],
            'value' => 'required',
            'origin' => 'required|in:client,team,ia',
        ], [
            'operator.in' => 'El operador seleccionado no es válido.',
            'requirement_key.unique' => 'Esta clave ya está en uso'
        ]);

        if (
            $validated['type'] === 'number' &&
            !is_numeric($validated['value'])
        ) {
            return back()
                ->withErrors(['value' => 'El valor debe ser numérico.'])
                ->withInput();
        }

        if (
            $validated['type'] === 'boolean' &&
            !in_array($validated['value'], ['0', '1'], true)
        ) {
            return back()
                ->withErrors(['value' => 'El valor debe ser verdadero o falso.'])
                ->withInput();
        }

        $requirement->update($validated);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', '¡Requisito actualizado correctamente!');
    }

    // Eliminar requisito
    public function destroy(Project $project, Requirement $requirement)
    {
        $requirement->delete();

        return redirect()
            ->route('projects.show', $project)
            ->with('success', '¡Requisito eliminado correctamente!');
    }
}
