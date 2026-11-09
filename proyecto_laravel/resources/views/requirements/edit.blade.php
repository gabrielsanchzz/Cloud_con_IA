<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar requisito</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<nav class="navbar">
    <div class="logo">Cloud_con_IA</div>
</nav>

<main class="container">

    <h1>Editar requisito</h1>

    <p class="subtitle">
        Proyecto: {{ $project->name }}
    </p>

    <div class="card">

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('requirements.update', [$project, $requirement]) }}">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="requirement_key">Clave del requisito</label>
                <input
                    type="text"
                    name="requirement_key"
                    id="requirement_key"
                    value="{{ old('requirement_key', $requirement->requirement_key) }}"
                    maxlength="80"
                    required
                >
            </div>

            <div class="form-group">
                <label for="type">Tipo de requisito</label>
                <input
                    type="text"
                    id="type"
                    value="{{ match($requirement->type) {
                        'number' => 'Numérico',
                        'text' => 'Texto',
                        'boolean' => 'Booleano',
                        default => $requirement->type
                    } }}"
                    disabled
                >
                <input type="hidden" name="type" value="{{ $requirement->type }}">
            </div>

            <div class="form-group">
                <label for="operator">Operador</label>
                <select name="operator" id="operator" required>
                    @php
                        $operator = old('operator', $requirement->operator);

                        $operators = match ($requirement->type) {
                            'number' => ['=', '!=', '>', '>=', '<', '<='],
                            'text' => ['=', '!=', 'contains'],
                            'boolean' => ['=', '!='],
                            default => [],
                        };
                    @endphp

                    @foreach ($operators as $option)
                        <option value="{{ $option }}" {{ $operator === $option ? 'selected' : '' }}>
                            {{ $option === 'contains' ? 'Contiene' : $option }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="value">Valor</label>

                @if ($requirement->type === 'boolean')
                    <select name="value" id="value" required>
                        <option value="1" {{ old('value', $requirement->value) == '1' ? 'selected' : '' }}>Verdadero</option>
                        <option value="0" {{ old('value', $requirement->value) == '0' ? 'selected' : '' }}>Falso</option>
                    </select>
                @else
                    <input
                        type="{{ $requirement->type === 'number' ? 'number' : 'text' }}"
                        name="value"
                        id="value"
                        value="{{ old('value', $requirement->value) }}"
                        required
                    >
                @endif
            </div>

            <div class="form-group">
                <label for="origin">Origen del requisito</label>
                <select name="origin" id="origin" required>
                    <option value="client" {{ old('origin', $requirement->origin) === 'client' ? 'selected' : '' }}>Cliente</option>
                    <option value="team" {{ old('origin', $requirement->origin) === 'team' ? 'selected' : '' }}>Equipo</option>
                    <option value="ia" {{ old('origin', $requirement->origin) === 'ia' ? 'selected' : '' }}>Inteligencia artificial</option>
                </select>
            </div>

            <button type="submit" class="btn">
                Guardar cambios
            </button>

            <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>

</main>

</body>
</html>
