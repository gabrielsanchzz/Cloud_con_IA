<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Archivos de {{ $proyecto->nombre }}</title>
</head>
<body>
    <h1>Archivos del proyecto: {{ $proyecto->nombre }}</h1>

    @if (session('ok'))
        <p style="color: green;">{{ session('ok') }}</p>
    @endif

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Subir archivo</h2>
    <form action="{{ route('archivos.store', $proyecto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="archivo">
        <button type="submit">Subir</button>
    </form>
    <p>Formatos permitidos: PDF, PNG y JPG. Máximo 1 MB.</p>

    <h2>Archivos subidos</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>Nombre</th>
            <th>Tamaño</th>
            <th>Subido</th>
            <th>Acciones</th>
        </tr>
        @forelse ($archivos as $archivo)
            <tr>
                <td>{{ $archivo->nombre_original }}</td>
                <td>{{ round($archivo->tamano / 1024) }} KB</td>
                <td>{{ $archivo->updated_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('archivos.show', $archivo) }}" target="_blank">Ver</a>

                    <form action="{{ route('archivos.update', $archivo) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="file" name="archivo">
                        <button type="submit">Sustituir</button>
                    </form>

                    <form action="{{ route('archivos.destroy', $archivo) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Este proyecto todavía no tiene archivos.</td>
            </tr>
        @endforelse
    </table>
</body>
</html>
