@component('mail::message')
# {{ $details['title'] }}

Hola, {{ $details['user'] }}. Te enviamos este mensaje porque se ha realizado una nueva venta, por valor de {{ $details['valor'] }}, en la plataforma.

@component('mail::button', ['url' => $details['url']])
Ver venta
@endcomponent

Gracias,<br>
{{-- {{ config('app.name') }} --}}
{{ "Lorgeliz Tienda"}}
@endcomponent