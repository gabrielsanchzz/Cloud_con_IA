<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva arquitectura</title>
</head>
<body>
    <h1>Nueva arquitectura manual</h1>

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('arquitecturas.store') }}" method="POST">
        @csrf

        <p>
            <label>Proyecto:
                <select name="proyecto_id">
                    @foreach ($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}" @selected(old('proyecto_id', $proyectoElegido) == $proyecto->id)>{{ $proyecto->nombre }}</option>
                    @endforeach
                </select>
            </label>
        </p>
        <p>
            <label>Nombre: <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="120"></label>
        </p>
        <p>
            <label>Tarifa:
                <select name="modalidad_tarifa">
                    @foreach ($modalidades as $m)
                        <option value="{{ $m->value }}" @selected(old('modalidad_tarifa', 'on_demand') === $m->value)>{{ $m->label() }}</option>
                    @endforeach
                </select>
            </label>
        </p>

        <h2>Elementos (mínimo 3 y de al menos 2 tipos distintos)</h2>
        @for ($i = 0; $i < $filas; $i++)
            <p>
                <strong>Línea {{ $i + 1 }}</strong>
                <select name="items[{{ $i }}][producto_id]">
                    <option value="">-- producto --</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}" @selected(old("items.$i.producto_id") == $producto->id)>
                            {{ $producto->nombre }} ({{ $producto->tipo }})
                        </option>
                    @endforeach
                </select>
                <select name="items[{{ $i }}][region_id]">
                    <option value="">-- región --</option>
                    @foreach ($regiones as $region)
                        <option value="{{ $region->id }}" @selected(old("items.$i.region_id") == $region->id)>{{ $region->nombre }}</option>
                    @endforeach
                </select>
                Cantidad <input type="number" name="items[{{ $i }}][cantidad]" value="{{ old("items.$i.cantidad", 1) }}" min="0">
                vCPU <input type="number" name="items[{{ $i }}][configuracion][vcpu]" value="{{ old("items.$i.configuracion.vcpu") }}" min="1" style="width: 60px;">
                RAM (GB) <input type="number" name="items[{{ $i }}][configuracion][ram_gb]" value="{{ old("items.$i.configuracion.ram_gb") }}" min="1" style="width: 60px;">
                Disco (GB) <input type="number" name="items[{{ $i }}][configuracion][disco_gb]" value="{{ old("items.$i.configuracion.disco_gb") }}" min="1" style="width: 70px;">
            </p>
        @endfor

        {{-- No hay ningún campo de precio ni de total: lo calcula el servidor --}}
        <button type="submit">Guardar arquitectura</button>
    </form>

    <p><a href="{{ route('arquitecturas.index') }}">Volver</a></p>
</body>
</html>
