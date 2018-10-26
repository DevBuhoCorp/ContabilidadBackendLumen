<?php

use Illuminate\Database\Seeder;

class RolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Rol::class)->create([
            'Descripcion' => 'Administrador',
            'Observacion' => 'Administrador',
            'Estado' => 'ACT'
        ]);
    }
}
