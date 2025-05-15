<x-mail::message>
# ¡Hola!

Hemos recibido una solicitud para actualizar la contraseña de tu cuenta.

<x-mail::button :url="$actionUrl" color="primary">
🔐 Crear nueva contraseña
</x-mail::button>

El enlace solo será válido por 60 minutos.

<x-mail::panel>
💡 Recomendación: Usa una combinación única de letras, números y símbolos.
</x-mail::panel>

Si no reconoces esta actividad, por favor ignora este mensaje.

Saludos,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>
