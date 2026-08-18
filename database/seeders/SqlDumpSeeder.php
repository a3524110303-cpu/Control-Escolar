<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SqlDumpSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('control-escolar.sql');
        if (! is_file($path)) {
            return;
        }

        $sql = file_get_contents($path);
        if ($sql === false) {
            throw new RuntimeException('No se pudo leer control-escolar.sql.');
        }

        foreach (['docentes', 'users'] as $table) {
            foreach ($this->rowsFor($sql, $table) as $row) {
                DB::table($table)->updateOrInsert(['id' => $row['id']], $row);
            }
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function rowsFor(string $sql, string $table): array
    {
        $pattern = '/INSERT INTO `'.preg_quote($table, '/').'` \(([^)]+)\) VALUES\s*(.*?);/s';
        if (! preg_match($pattern, $sql, $insert)) {
            return [];
        }

        $columns = array_map(
            fn (string $column): string => trim($column, " `\t\r\n"),
            explode(',', $insert[1])
        );

        preg_match_all('/^\s*\((.*)\),?\s*$/m', trim($insert[2]), $matches);

        return array_map(function (string $line) use ($columns): array {
            $values = str_getcsv($line, ',', "'", '\\');
            $values = array_map(function (string $value): mixed {
                $value = trim($value);

                return strtoupper($value) === 'NULL' ? null : $value;
            }, $values);

            return array_combine($columns, $values);
        }, $matches[1]);
    }
}
