param(
    [int]$Port = 3307
)

$mysqlAdmin = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqladmin.exe'
& $mysqlAdmin --host=127.0.0.1 --port=$Port --user=root shutdown
