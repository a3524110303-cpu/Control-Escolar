# Control Escolar

Aplicación local en Laravel para administrar alumnos, docentes, grupos, materias,
periodos, cargas horarias, asistencia, calificaciones y trámites escolares.

## Inicio rápido en este equipo

Desde PowerShell, dentro de la carpeta del proyecto:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\start-local-app.ps1
```

El script inicia la instancia MySQL aislada en `127.0.0.1:3307`, aplica las
migraciones pendientes y sirve la aplicación en <http://127.0.0.1:8000>.
El servidor web se detiene con `Ctrl+C`. Para apagar también MySQL:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\stop-local-mysql.ps1
```

La información de MySQL se conserva en `storage/mysql-data`; no utiliza ni
modifica la instancia MySQL existente en el puerto 3306.

## Preparación desde cero

1. Copiar `.env.example` como `.env` y ejecutar `php artisan key:generate`.
2. Instalar paquetes PHP con `composer install`.
3. Instalar y compilar recursos con `npm install` y `npm run build`.
4. Iniciar MySQL con `scripts/start-local-mysql.ps1`.
5. Ejecutar `php artisan migrate --seed`.

El archivo `control-escolar.sql` recibido contiene datos, pero no las sentencias
`CREATE TABLE`. Las migraciones de Laravel son la definición completa del esquema.
El seeder recupera de ese archivo solamente usuarios y docentes; descarta sesiones
y el historial viejo de migraciones para evitar datos temporales o incompatibles.

## Verificación

```powershell
php artisan test
vendor\bin\pint --test
npm run build
composer audit --no-dev
npm audit
```

Las pruebas automatizadas usan SQLite en memoria para ser rápidas y aisladas. La
aplicación local usa MySQL 8 y se valida adicionalmente recreando sus migraciones.
