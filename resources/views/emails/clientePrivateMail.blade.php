@component('mail::message')
# {{ ("Nuevo mensaje") }}

{{ $message }}

@component('mail::button', ['url' => url('/cuenta')])
Ir a mi cuenta
@endcomponent

Atentamente,<br>
{{-- {{ config('app.name') }} --}}
{{ "Lorgeliz Tienda"}}
@endcomponent