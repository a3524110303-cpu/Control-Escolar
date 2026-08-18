<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Administrador', 'Director', 'Docente', 'Alumno'] as $role) {
            DB::table('roles')->updateOrInsert(
                ['nombre' => $role],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        foreach (['Acta de nacimiento', 'CURP', 'Certificado', 'Comprobante de domicilio'] as $name) {
            TipoDocumento::firstOrCreate(['nombre' => $name], ['obligatorio' => true]);
        }

        $this->call(SqlDumpSeeder::class);
    }
}
