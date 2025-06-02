
<h1>¡Te han invitado a nuestro ERP!</h1>
<p>Estimado/a,</p>
<p>Hemos creado tu cuenta en el ERP. Ingresa usando tu correo y la contraseña temporal:</p>
<ul>
    <li>Email: {{ $notifiable->email ?? '...' }}</li>
    <li>Contraseña temporal: <strong>{{ $passwordTemp }}</strong></li>
</ul>
<p>Para activar tu cuenta y cambiar la contraseña, haz clic en el siguiente enlace:</p>
<p><a href="{{ $urlActivacion }}">{{ $urlActivacion }}</a></p>
<p>Saludos.</p>
<p>El equipo de {{ config('app.name') }}</p>
<p>Si no has solicitado esta invitación, por favor ignora este mensaje.</p>