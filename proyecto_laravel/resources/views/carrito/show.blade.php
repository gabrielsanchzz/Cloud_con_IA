<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de {{ $proyecto->nombre }}</title>
</head>
<body>
    <h1>Carrito del proyecto: {{ $proyecto->nombre }}</h1>

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

    <h2>Añadir producto</h2>
    <form action="{{ route('carrito.agregar', $proyecto) }}" method="POST">
        @csrf
        <select name="producto_id">
            <option value="">-- Elige un producto --</option>
            @foreach ($productos as $producto)
                <option value="{{ $producto->id }}" @selected(old('producto_id') == $producto->id)>
                    {{ $producto->nombre }} ({{ $producto->precio }} € {{ $producto->modalidad }})
                </option>
            @endforeach
        </select>
        <input type="number" name="cantidad" value="{{ old('cantidad', 1) }}" min="1" max="100">
        <button type="submit">Añadir</button>
    </form>

    <h2>Productos en el carrito</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Precio</th>
            <th>Pago</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
        @forelse ($lineas as $linea)
            <tr>
                <td>{{ $linea->producto->nombre }}</td>
                <td>{{ $linea->producto->tipo }}</td>
                <td>{{ $linea->producto->precio }} €</td>
                <td>{{ $linea->producto->modalidad }}</td>
                <td>
                    <form action="{{ route('carrito.actualizar', $linea) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="cantidad" value="{{ $linea->cantidad }}" min="1" max="100">
                        <button type="submit">Cambiar</button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('carrito.quitar', $linea) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Quitar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">El carrito está vacío.</td>
            </tr>
        @endforelse
    </table>

    <p>Total mensual: <strong>{{ number_format($totales['mensual'], 2, ',', '.') }} €</strong></p>
    <p>Total pago único: <strong>{{ number_format($totales['unico'], 2, ',', '.') }} €</strong></p>

    <form action="{{ route('presupuestos.store', $proyecto) }}" method="POST">
        @csrf
        <button type="submit">Emitir presupuesto</button>
    </form>

    <h2>Presupuestos emitidos</h2>
    <ul>
        @forelse ($presupuestos as $presupuesto)
            <li>
                <a href="{{ route('presupuestos.show', $presupuesto) }}">
                    Presupuesto #{{ $presupuesto->id }} del {{ $presupuesto->emitido_en->format('d/m/Y H:i') }}
                </a>
            </li>
        @empty
            <li>Todavía no hay presupuestos.</li>
        @endforelse
    </ul>
</body>
</html>
