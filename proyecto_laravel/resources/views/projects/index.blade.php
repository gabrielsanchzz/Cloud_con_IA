<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos | Cloud_con_IA</title>
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

    <h1>Mis proyectos</h1>
    <p class="subtitle">Gestiona y consulta tus proyectos registrados.</p>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('projects.create') }}" class="btn">
        + Crear nuevo proyecto
    </a>

    <br><br>

    @forelse ($projects as $project)

        <article class="project-card">
            <h2>{{ $project->name }}</h2>

            <p>
                {{ $project->description ?: 'Sin descripción disponible.' }}
            </p>

            <p>
                <strong>Presupuesto mensual:</strong>
                {{ number_format($project->monthly_budget, 2, ',', '.') }} €
            </p>

            <a href="{{ route('projects.show', $project) }}" class="btn">
                Ver detalles
            </a>
        </article>

    @empty

        <section class="card">
            <h2>Aún no tienes proyectos</h2>
            <p>Crea tu primer proyecto para empezar a gestionarlo.</p>
        </section>

    @endforelse

</main>

<footer class="footer">
    Cloud_con_IA · Plataforma de gestión de proyectos
</footer>

</body>
</html>