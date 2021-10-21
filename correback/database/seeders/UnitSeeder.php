<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
            ['nombre'=>'CATASTRO URBANO','codigo'=>'CAT'],
            ['nombre'=>'CENTRO IMAGENOLOGIA','codigo'=>'IMA'],
            ['nombre'=>'INDUSTRIA Y COMERCIO','codigo'=>'IND'],
            ['nombre'=>'ESPECTACULOS PUBLICOS','codigo'=>'ESP'],
            ['nombre'=>'CONTROL MERCADOS','codigo'=>'MER'],
            ['nombre'=>'REGULACION URBANA','codigo'=>'REG'],
            ['nombre'=>'CEMENTERIO GENERAL','codigo'=>'CEM'],
            ['nombre'=>'REGULACION URBANA DISTRITO 3','codigo'=>'DIS'],
            ['nombre'=>'ARBITRIOS MUNICIPALES','codigo'=>'ARB'],
            ['nombre'=>'UNIDAD VALORES','codigo'=>'VAL'],
            ['nombre'=>'DIRECCION TRAFICO y VIALIDAD','codigo'=>'TRA'],
            ['nombre'=>'COACTIVA','codigo'=>'COA'],
            ['nombre'=>'DIRECCION CASA MUNICIPAL DE CULTURA','codigo'=>'MUN'],
            ['nombre'=>'DIRECCION PP SALUD','codigo'=>'SAL'],
            ['nombre'=>'REGISTRO UNICO AUTOMOTOR','codigo'=>'AUT'],
            ['nombre'=>'UNIDAD SISTEMA','codigo'=>'SIS'],
        ]);
    }
}
