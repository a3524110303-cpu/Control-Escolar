param(
    [int]$AppPort = 8000,
    [int]$DatabasePort = 3307
)

$ErrorActionPreference = 'Stop'
$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path
$php = 'C:\laragon\bin\php\php-8.3.26-Win32-vs16-x64\php.exe'

if (-not (Test-Path -LiteralPath $php)) {
    throw "No se encontró PHP en $php"
}

& (Join-Path $PSScriptRoot 'start-local-mysql.ps1') -Port $DatabasePort
if ($LASTEXITCODE -ne 0) {
    throw 'No se pudo iniciar MySQL local.'
}

Push-Location $projectRoot
try {
    & $php artisan migrate --force
    if ($LASTEXITCODE -ne 0) {
        throw 'No se pudieron aplicar las migraciones.'
    }

    Write-Output "Control Escolar disponible en http://127.0.0.1:$AppPort"
    Write-Output 'Presiona Ctrl+C para detener el servidor web.'
    & $php artisan serve --host=127.0.0.1 --port=$AppPort
} finally {
    Pop-Location
}
