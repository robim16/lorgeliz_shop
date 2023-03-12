@component('mail::message')
# {{ ("Hemos actualizado el estado de tu solicitud") }}

Hola, {{ $details['cliente'] }}. Te enviamos este mensaje porque la solicitud que hiciste el día {{ $details['fecha'] }}, para pedir el cambio de productos, 
@if ($details['estado'] == 2) 
{{ "está en proceso" }}
@endif
@if ($details['estado'] == 3) 
{{ "ha sido rechazada" }}
@endif
@if ($details['estado'] == 4) 
{{ "se ha completado" }}
@endif
en este momento.

@component('mail::button', ['url' => $details['url']])
Ver mi solicitud de cambio de productos
@endcomponent

Gracias,<br>
{{-- {{ config('app.name') }} --}}
{{ "Lorgeliz Tienda"}}
@endcomponent
