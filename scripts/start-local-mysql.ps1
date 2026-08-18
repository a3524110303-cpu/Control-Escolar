param(
    [int]$Port = 3307
)

$ErrorActionPreference = 'Stop'
$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path
$mysqlRoot = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64'
$mysqld = Join-Path $mysqlRoot 'bin\mysqld.exe'
$mysql = Join-Path $mysqlRoot 'bin\mysql.exe'
$mysqlAdmin = Join-Path $mysqlRoot 'bin\mysqladmin.exe'
$dataDir = Join-Path $projectRoot 'storage\mysql-data'
$logFile = Join-Path $projectRoot 'storage\logs\mysql-local.log'
$pidFile = Join-Path $projectRoot 'storage\mysql-local.pid'

if (-not (Test-Path -LiteralPath $mysqld)) {
    throw "No se encontró MySQL en $mysqlRoot"
}

$listening = Get-NetTCPConnection -LocalPort $Port -State Listen -ErrorAction SilentlyContinue
if ($listening) {
    & $mysqlAdmin --host=127.0.0.1 --port=$Port --user=root ping --silent
    exit $LASTEXITCODE
}

if (-not (Test-Path -LiteralPath (Join-Path $dataDir 'mysql'))) {
    New-Item -ItemType Directory -Path $dataDir -Force | Out-Null
    & $mysqld --no-defaults --initialize-insecure --basedir="$mysqlRoot" --datadir="$dataDir" --log-error="$logFile"
    if ($LASTEXITCODE -ne 0) {
        throw 'No se pudo inicializar la base MySQL local.'
    }
}

$arguments = @(
    '--no-defaults',
    "--basedir=`"$mysqlRoot`"",
    "--datadir=`"$dataDir`"",
    "--port=$Port",
    '--bind-address=127.0.0.1',
    '--mysqlx=0',
    "--pid-file=`"$pidFile`"",
    "--log-error=`"$logFile`""
)

Start-Process -FilePath $mysqld -ArgumentList $arguments -WindowStyle Hidden | Out-Null

for ($attempt = 0; $attempt -lt 30; $attempt++) {
    Start-Sleep -Milliseconds 500
    & $mysqlAdmin --host=127.0.0.1 --port=$Port --user=root ping --silent 2>$null
    if ($LASTEXITCODE -eq 0) {
        & $mysql --host=127.0.0.1 --port=$Port --user=root --execute='CREATE DATABASE IF NOT EXISTS `control-escolar` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'
        Write-Output "MySQL local disponible en 127.0.0.1:$Port"
        exit 0
    }
}

throw "MySQL no respondió en el puerto $Port. Revisa $logFile"
