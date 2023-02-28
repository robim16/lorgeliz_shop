@component('mail::message')
# {{ $details['title'] }}

Hola, {{ $details['user'] }}. Te enviamos este mensaje porque el cliente {{ $details['cliente'] }} ha solicitado un cambio de producto.

@component('mail::button', ['url' => $details['url']])
Ver solicitud
@endcomponent

Gracias,<br>
{{-- {{ config('app.name') }} --}}
{{ "Lorgeliz Tienda"}}
@endcomponent