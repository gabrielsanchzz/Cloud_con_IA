<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo requisito</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<nav class="navbar">
    <div class="logo">Cloud_con_IA</div>
</nav>

<main class="container">

    <h1>Añadir requisito</h1>

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

        @if ($step === 1)

            <h2>Paso 1: Selecciona el tipo</h2>

            <form method="POST"
                  action="{{ route('requirements.step-two', $project) }}">

                @csrf

                <div class="form-group">
                    <label for="type">Tipo de requisito</label>

                    <select name="type" id="type" required>
                        <option value="">Selecciona un tipo</option>

                        <option value="number"
                            {{ old('type') == 'number' ? 'selected' : '' }}>
                            Numérico
                        </option>

                        <option value="text"
                            {{ old('type') == 'text' ? 'selected' : '' }}>
                            Texto
                        </option>

                        <option value="boolean"
                            {{ old('type') == 'boolean' ? 'selected' : '' }}>
                            Booleano
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn">
                    Continuar
                </button>

                <a href="{{ route('projects.show', $project) }}"
                   class="btn btn-secondary">
                    Volver al proyecto
                </a>

            </form>

        @else

            <h2>Paso 2: Completa el requisito</h2>

            <p>Tipo seleccionado:
                <strong>{{ $type }}</strong>
            </p>

            <form method="POST"
                  action="{{ route('requirements.store', $project) }}">

                @csrf

                <input type="hidden" name="type" value="{{ $type }}">

                <div class="form-group">
                    <label for="requirement_key">Clave del requisito</label>
                    <input
                        type="text"
                        name="requirement_key"
                        id="requirement_key"
                        value="{{ old('requirement_key') }}"
                        maxlength="80"
                        placeholder="Ejemplo: edad_minima"
                        required>
                </div>

                <div class="form-group">
                    <label for="operator">Operador</label>

                    <select name="operator" id="operator" required>

                        @if ($type === 'number')
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                            <option value=">">&gt;</option>
                            <option value=">=">&gt;=</option>
                            <option value="<">&lt;</option>
                            <option value="<=">&lt;=</option>

                        @elseif ($type === 'text')
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                            <option value="contains">Contiene</option>

                        @elseif ($type === 'boolean')
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                        @endif

                    </select>
                </div>

                <div class="form-group">
                    <label for="value">Valor</label>

                    @if ($type === 'number')
                        <input type="number"
                               name="value"
                               id="value"
                               value="{{ old('value') }}"
                               step="any"
                               required>

                    @elseif ($type === 'text')
                        <input type="text"
                               name="value"
                               id="value"
                               value="{{ old('value') }}"
                               required>

                    @elseif ($type === 'boolean')
                        <select name="value" id="value" required>
                            <option value="1">Verdadero</option>
                            <option value="0">Falso</option>
                        </select>
                    @endif
                </div>

                <div class="form-group">
                    <label for="origin">Origen del requisito</label>

                    <select name="origin" id="origin" required>
                        <option value="client">Cliente</option>
                        <option value="team">Equipo</option>
                        <option value="ia">Inteligencia artificial</option>
                    </select>
                </div>

                <button type="submit" class="btn">
                    Guardar requisito
                </button>

                <a href="{{ route('requirements.create', $project) }}"
                   class="btn btn-secondary">
                    Volver al paso 1
                </a>

            </form>

        @endif

    </div>

</main>

</body>
</html>