<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del proyecto</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    <nav class="navbar">
        <div class="logo">Cloud_con_IA</div>
    </nav>

    <main class="container">

        <h1>Detalle del proyecto</h1>

        <p class="subtitle">
            Información general y requisitos del proyecto.
        </p>

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">

            <h2>{{ $project->name }}</h2>

            <div class="form-group">
                <strong>Descripción:</strong>
                <p>{{ $project->description ?: 'Sin descripción' }}</p>
            </div>

            <div class="form-group">
                <strong>Necesidades:</strong>
                <p>{{ $project->needs ?: 'Sin necesidades especificadas' }}</p>
            </div>

            <div class="form-group">
                <strong>Presupuesto mensual:</strong>
                <p>{{ number_format($project->monthly_budget, 2, ',', '.') }} €</p>
            </div>

            <hr>

            <h2>Requisitos del proyecto</h2>

            <a href="{{ route('requirements.create', $project) }}" class="btn">
                Añadir requisito
            </a>

            @if ($project->requirements->isEmpty())

                <p class="subtitle">
                    Todavía no hay requisitos registrados.
                </p>

            @else

                <div class="table-container">
                    <table class="requirements-table">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Tipo</th>
                                <th>Operador</th>
                                <th>Valor</th>
                                <th>Origen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($project->requirements as $requirement)
                                <tr>
                                    <td>{{ $requirement->requirement_key }}</td>

                                    <td>
                                        @if ($requirement->type === 'number')
                                            Numérico
                                        @elseif ($requirement->type === 'text')
                                            Texto
                                        @else
                                            Booleano
                                        @endif
                                    </td>

                                    <td>
                                        {{ $requirement->operator === 'contains' ? 'Contiene' : $requirement->operator }}
                                    </td>

                                    <td>
                                        @if ($requirement->type === 'boolean')
                                            {{ $requirement->value == '1' ? 'Verdadero' : 'Falso' }}
                                        @else
                                            {{ $requirement->value }}
                                        @endif
                                    </td>

                                    <td>
                                        @if ($requirement->origin === 'client')
                                            Cliente
                                        @elseif ($requirement->origin === 'team')
                                            Equipo
                                        @else
                                            Inteligencia artificial
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('requirements.edit', [$project, $requirement]) }}"
                                           class="btn btn-secondary">
                                            Editar
                                        </a>

                                        <form method="POST"
                                              action="{{ route('requirements.destroy', [$project, $requirement]) }}"
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Seguro que quieres eliminar este requisito?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif

            <hr>

            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                Volver a los proyectos
            </a>

        </div>

    </main>

    <footer class="footer">
        <p>Cloud_con_IA - Gestión de proyectos</p>
    </footer>

</body>
</html>
