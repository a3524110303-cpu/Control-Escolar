<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema | Bachillerato Juan de Dios Peza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bgo-navy: #0d2b7d;
            --bgo-cyan: #1daeef;
            --bgo-bg: #f8fafc;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bgo-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            background-image: radial-gradient(circle at 10% 20%, rgba(29, 174, 239, 0.05) 0%, transparent 80%);
        }
        .card-login {
            width: 100%;
            max-width: 440px;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(13, 43, 125, 0.08);
            background: #ffffff;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #ffffff 0%, #f4f8ff 100%);
            border-bottom: 2px solid #eef4ff;
        }
        .btn-bgo {
            background-color: var(--bgo-navy);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-bgo:hover {
            background-color: #081d59;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 43, 125, 0.25);
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: var(--bgo-cyan);
            box-shadow: 0 0 0 4px rgba(29, 174, 239, 0.15);
        }
        .text-bgo-navy { color: var(--bgo-navy); }
        .text-bgo-cyan { color: var(--bgo-cyan); }
    </style>
</head>
<body>

<div class="card card-login">
    <!-- Se eliminó la etiqueta <img> de aquí -->
    <div class="login-header text-center p-4">
        <h5 class="fw-bold text-bgo-navy mb-1">Control Escolar</h5>
        <p class="text-muted small mb-0 font-medium">Bachillerato General Oficial Juan de Dios Peza</p>
        <p class="text-muted small fw-medium mb-0">C.C.T. 21EBH0949I</p>
    </div>

    <div class="p-4">
        @if (session('status'))
            <div class="alert alert-success border-0 small mb-3 text-center" style="background-color: #e6f7ff; color: #006699; border-radius: 10px;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Campo ajustado a "Usuario" -->
            <div class="mb-3">
                <label for="identificador" class="form-label text-bgo-navy small fw-semibold">Usuario</label>
                <input type="text" name="identificador" id="identificador" class="form-control @error('identificador') is-invalid @enderror" value="{{ old('identificador') }}" placeholder="Ingresa tu CURP o NIA" required autofocus style="text-transform:uppercase;">
                @error('identificador')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-bgo-navy small fw-semibold">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                @error('password')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('password.request') }}" class="text-decoration-none small text-bgo-cyan fw-medium">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn btn-bgo w-100">Iniciar Sesión</button>
        </form>
    </div>
</div>

</body>
</html>