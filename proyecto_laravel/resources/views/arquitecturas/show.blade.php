<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $arquitectura->nombre }}</title>
</head>
<body>
    <h1>Arquitectura: {{ $arquitectura->nombre }}</h1>
    <p>Proyecto: {{ $arquitectura->proyecto->nombre }}
        (presupuesto máximo: {{ number_format((float) $arquitectura->proyecto->presupuesto_maximo, 2, ',', '.') }} €/mes)</p>

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

    {{-- GET: cambiar de tarifa recalcula en el servidor, no guarda nada --}}
    <form action="{{ route('arquitecturas.show', $arquitectura) }}" method="GET">
        <label>Tarifa:
            <select name="modalidad">
                @foreach ($modalidades as $m)
                    <option value="{{ $m->value }}" @selected($modalidad === $m)>{{ $m->label() }}</option>
                @endforeach
            </select>
        </label>
        <button type="submit">Recalcular</button>
    </form>

    <h2>Elementos</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Región</th>
            <th>Cantidad</th>
            <th>Configuración</th>
            <th>Mensual</th>
            <th>Pago único</th>
        </tr>
        @foreach ($quote->lines as $lq)
            @php($item = $arquitectura->items->firstWhere('id', $lq->line->itemId))
            <tr>
                <td>{{ $lq->line->productName }}</td>
                <td>{{ $item->producto->tipo }}</td>
                <td>{{ $lq->line->regionName }}</td>
                <td>{{ $lq->line->quantity }}</td>
                <td>
                    @forelse (($item->configuracion ?? []) as $clave => $valor)
                        {{ $clave }}: {{ $valor }}@if (! $loop->last), @endif
                    @empty
                        -
                    @endforelse
                </td>
                <td>{{ \App\Support\Money::format($lq->monthlyCents) }}</td>
                <td>{{ \App\Support\Money::format($lq->oneTimeCents) }}</td>
            </tr>
        @endforeach
        <tr><th colspan="5">Total mensual</th><th colspan="2">{{ \App\Support\Money::format($quote->monthlyTotalCents) }}</th></tr>
        <tr><th colspan="5">Total pago único</th><th colspan="2">{{ \App\Support\Money::format($quote->oneTimeTotalCents) }}</th></tr>
        <tr><th colspan="5">Coste del primer año</th><th colspan="2">{{ \App\Support\Money::format($quote->firstYearTotalCents()) }}</th></tr>
    </table>

    <h2>Reglas de negocio</h2>
    @forelse ($informe->results as $resultado)
        <x-regla-alerta :resultado="$resultado" />
    @empty
        <p>Se cumplen todas las reglas.</p>
    @endforelse

    @if ($informe->canIssueBudget())
        <p>Se puede continuar@if ($informe->hasWarnings()) (hay avisos, pero no impiden emitir el presupuesto)@endif.</p>
        <form action="{{ route('arquitecturas.carrito', $arquitectura) }}" method="POST">
            @csrf
            <button type="submit">Añadir al carrito</button>
        </form>
    @else
        <p style="color: red;"><strong>No se puede emitir presupuesto hasta resolver los errores.</strong></p>
    @endif

    <p><a href="{{ route('arquitecturas.index') }}">Volver a las arquitecturas</a></p>
</body>
</html>
