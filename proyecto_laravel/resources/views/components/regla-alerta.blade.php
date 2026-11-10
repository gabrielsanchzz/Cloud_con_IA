@props(['resultado'])
{{-- Componente reutilizable: una regla incumplida. {{ }} escapa el HTML (evita XSS). --}}
@if ($resultado->severity === \App\Enums\Severidad::Block)
    <p style="color: red;"><strong>Error {{ $resultado->code }}:</strong> {{ $resultado->message }}</p>
@else
    <p style="color: darkorange;"><strong>Aviso {{ $resultado->code }}:</strong> {{ $resultado->message }}</p>
@endif
