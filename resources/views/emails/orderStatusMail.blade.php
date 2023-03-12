@component('mail::message')
# {{ ("Hemos actualizado el estado de tu pedido") }}

Hola, {{ $details['cliente'] }}. Te enviamos este mensaje porque tu pedido, realizado el día {{ $details['fecha'] }} está siendo
@if ($details['estado'] == 2)
{{"procesado"}}
@endif
@if ($details['estado'] == 3)
{{"enviado"}}
@endif
@if ($details['estado'] == 4)
{{"entregado"}}
@endif
en este momento.

@component('mail::button', ['url' => $details['url']])
Ver pedido
@endcomponent

Gracias,<br>
{{-- {{ config('app.name') }} --}}
{{ "Lorgeliz Tienda"}}
@endcomponent
