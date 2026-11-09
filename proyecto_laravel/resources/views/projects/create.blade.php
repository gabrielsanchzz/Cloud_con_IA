<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear proyecto | Cloud_con_IA</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<header class="navbar">
    <div class="logo">☁ Cloud_con_IA</div>
    <nav>
        <a href="{{ route('projects.index') }}">Proyectos</a>
        <a href="{{ route('projects.create') }}">Crear proyecto</a>
    </nav>
</header>

<main class="container">

    <h1>Crear nuevo proyecto</h1>
    <p class="subtitle">Completa la información para registrar un nuevo proyecto.</p>

    <section class="card">

        @if ($errors->any())
            <div class="error">
                <strong>Hay errores en el formulario:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nombre del proyecto</label>
                <input type="text" id="name" name="name"
                    value="{{ old('name') }}"
                    placeholder="Ej. Plataforma de gestión"
                    maxlength="120" required>
            </div>

            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea id="description" name="description"
                    maxlength="1000"
                    placeholder="Describe brevemente el proyecto...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="needs">Necesidades</label>
                <textarea id="needs" name="needs"
                    maxlength="1000"
                    placeholder="¿Qué necesidades pretende cubrir?">{{ old('needs') }}</textarea>
            </div>

            <div class="form-group">
                <label for="monthly_budget">Presupuesto mensual (€)</label>
                <input type="number" id="monthly_budget"
                    name="monthly_budget"
                    value="{{ old('monthly_budget') }}"
                    min="0" step="0.01" required
                    placeholder="250.00">
            </div>

            <button type="submit" class="btn">
                Guardar proyecto
            </button>

            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </form>

    </section>

</main>

<footer class="footer">
    Cloud_con_IA · Plataforma de gestión de proyectos
</footer>

</body>
</html>
