<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Arquitecturas Cloud</title>
</head>
<body>
    <h1>Arquitecturas Cloud</h1>

    @if (session('ok'))
        <p style="color: green;">{{ session('ok') }}</p>
    @endif

    <p><a href="{{ route('arquitecturas.create') }}">Nueva arquitectura</a></p>

    <table border="1" cellpadding="5">
        <tr>
            <th>Nombre</th>
            <th>Proyecto</th>
            <th>Elementos</th>
            <th>Tarifa</th>
            <th>Acciones</th>
        </tr>
        @forelse ($arquitecturas as $arquitectura)
            <tr>
                <td><a href="{{ route('arquitecturas.show', $arquitectura) }}">{{ $arquitectura->nombre }}</a></td>
                <td>{{ $arquitectura->proyecto->nombre }}</td>
                <td>{{ $arquitectura->items_count }}</td>
                <td>{{ $arquitectura->modalidad_tarifa->label() }}</td>
                <td>
                    <form action="{{ route('arquitecturas.destroy', $arquitectura) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Todavía no hay arquitecturas.</td></tr>
        @endforelse
    </table>

    {{ $arquitecturas->links() }}
</body>
</html>
