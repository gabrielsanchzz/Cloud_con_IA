<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto #{{ $presupuesto->id }}</title>
</head>
<body>
    <h1>Presupuesto #{{ $presupuesto->id }}</h1>

    @if (session('ok'))
        <p style="color: green;">{{ session('ok') }}</p>
    @endif

    <p>Proyecto: {{ $presupuesto->nombre_proyecto }}</p>
    <p>Emitido el: {{ $presupuesto->emitido_en->format('d/m/Y H:i') }}</p>

    <table border="1" cellpadding="5">
        <tr>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Pago</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
        @foreach ($presupuesto->lineas as $linea)
            <tr>
                <td>{{ $linea->nombre_producto }}</td>
                <td>{{ $linea->tipo_producto }}</td>
                <td>{{ $linea->modalidad }}</td>
                <td>{{ $linea->precio_unitario }} €</td>
                <td>{{ $linea->cantidad }}</td>
                <td>{{ $linea->subtotal }} €</td>
            </tr>
        @endforeach
    </table>

    <p>Total mensual: <strong>{{ number_format($presupuesto->total_mensual, 2, ',', '.') }} €</strong></p>
    <p>Total pago único: <strong>{{ number_format($presupuesto->total_unico, 2, ',', '.') }} €</strong></p>

    <p><a href="{{ route('carrito.show', $presupuesto->proyecto_id) }}">Volver al carrito</a></p>
</body>
</html>
