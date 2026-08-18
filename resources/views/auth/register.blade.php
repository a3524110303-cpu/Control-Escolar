<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setup ? 'Configuración inicial' : 'Alta de usuario' }} | Control Escolar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f3f7fb; min-height: 100vh; }
        .card { max-width: 560px; border: 0; border-radius: 20px; box-shadow: 0 14px 40px rgba(13,43,125,.10); }
        .btn-primary { background: #0d2b7d; border-color: #0d2b7d; }
    </style>
</head>
<body class="d-flex align-items-center py-5">
    <main class="container">
        <div class="card mx-auto">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height:76px" class="mb-3">
                    <h1 class="h4 text-primary fw-bold">{{ $setup ? 'Configuración inicial' : 'Alta de usuario' }}</h1>
                    <p class="text-secondary small mb-0">
                        {{ $setup ? 'Crea el primer administrador del sistema.' : 'Solo un administrador puede crear cuentas.' }}
                    </p>
                </div>

                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <form method="POST" action="{{ $setup ? route('setup.store') : route('users.store') }}" class="row g-3">
                    @csrf
                    @unless ($setup)
                        <div class="col-12">
                            <label for="rol" class="form-label">Rol</label>
                            <select name="rol" id="rol" class="form-select" required>
                                @foreach (\App\Models\User::ROLES as $role)
                                    <option value="{{ $role }}" @selected(old('rol') === $role)>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endunless
                    <div class="col-12">
                        <label for="identificador" class="form-label">Identificador (CURP o NIA)</label>
                        <input id="identificador" name="identificador" value="{{ old('identificador') }}" class="form-control text-uppercase" minlength="4" maxlength="30" required autofocus>
                    </div>
                    <div class="col-12">
                        <label for="email_recuperacion" class="form-label">Correo de recuperación</label>
                        <input type="email" id="email_recuperacion" name="email_recuperacion" value="{{ old('email_recuperacion') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña temporal</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="col-12 small text-secondary">Mínimo 12 caracteres, mayúsculas, minúsculas, números y símbolos.</div>
                    <div class="col-12 d-grid"><button class="btn btn-primary py-2">Crear usuario</button></div>
                    <div class="col-12 text-center"><a href="{{ $setup ? route('login') : route('dashboard') }}" class="small">Volver</a></div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
