<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Requirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequirementTest extends TestCase
{
    use RefreshDatabase;

    private function createProject(): Project
    {
        return Project::create([
            'name' => 'Proyecto de prueba',
            'description' => 'Descripción de prueba',
            'needs' => 'Gestión de información',
            'monthly_budget' => 250,
        ]);
    }

    public function test_a_requirement_can_be_created(): void
    {
        $project = $this->createProject();

        $response = $this->post(
            route('requirements.store', $project),
            [
                'requirement_key' => 'edad_minima',
                'type' => 'number',
                'operator' => '>=',
                'value' => '18',
                'origin' => 'client',
            ]
        );

        $response->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('requirements', [
            'project_id' => $project->id,
            'requirement_key' => 'edad_minima',
            'value' => '18',
        ]);
    }

    public function test_a_requirement_can_be_updated(): void
    {
        $project = $this->createProject();

        $requirement = $project->requirements()->create([
            'requirement_key' => 'edad_minima',
            'type' => 'number',
            'operator' => '>=',
            'value' => '18',
            'origin' => 'client',
        ]);

        $response = $this->put(
            route('requirements.update', [$project, $requirement]),
            [
                'requirement_key' => 'edad_minima',
                'type' => 'number',
                'operator' => '>=',
                'value' => '21',
                'origin' => 'client',
            ]
        );

        $response->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('requirements', [
            'id' => $requirement->id,
            'value' => '21',
        ]);
    }

    public function test_a_requirement_can_be_deleted(): void
    {
        $project = $this->createProject();

        $requirement = $project->requirements()->create([
            'requirement_key' => 'edad_minima',
            'type' => 'number',
            'operator' => '>=',
            'value' => '18',
            'origin' => 'client',
        ]);

        $response = $this->delete(
            route('requirements.destroy', [$project, $requirement])
        );

        $response->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseMissing('requirements', [
            'id' => $requirement->id,
        ]);
    }

    public function test_a_text_requirement_rejects_an_invalid_operator(): void
    {
        $project = $this->createProject();

        $response = $this->post(
            route('requirements.store', $project),
            [
                'requirement_key' => 'nombre',
                'type' => 'text',
                'operator' => '>=',
                'value' => 'Gabriel',
                'origin' => 'client',
            ]
        );

        $response->assertSessionHasErrors('operator');

        $this->assertDatabaseMissing('requirements', [
            'project_id' => $project->id,
            'requirement_key' => 'nombre',
        ]);
    }
}
