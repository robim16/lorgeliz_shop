@component('mail::message')
# {{ $details['title'] }}

Hola, {{ $details['cliente'] }}. Te enviamos este mensaje porque has solicitado un pedido en nuestra tienda.
En breves instantes empezaremos a atender tu pedido.

@component('mail::button', ['url' => $details['url']])
    Ver pedido
@endcomponent

Gracias por tu compra,<br>
{{-- {{ config('app.name') }} --}}
{{ "Lorgeliz Tienda"}}
@endcomponent