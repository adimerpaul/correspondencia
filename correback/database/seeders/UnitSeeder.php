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
            ['nombre'=>'ALCALDE MUNICIPAL','codigo'=>'ALMU'],
            ['nombre'=>'SUB ALCALDIA D-3','codigo'=>'SUBALD3'],
            ['nombre'=>'SECRETARIA GENERAL','codigo'=>'SEGE'],
            ['nombre'=>'SECRETARIA MPAL. DE ECON.Y HACIENDA','codigo'=>'SEMUECHA'],
            ['nombre'=>'SECRETARIA MPAL. DE INFRAESTRUCTURA. PUBLICA','codigo'=>'SEMUINPU'],
            ['nombre'=>'SECRETARIA MPAL. DE GESTION TERRITORIAL','codigo'=>'SEMUGETE'],
            ['nombre'=>'SECRETARIA MPAL. DE CULTURA','codigo'=>'SEMUCU'],
            ['nombre'=>'SECRETARIA MPAL. DE DESRROLLO HUMANO','codigo'=>'SEMUDEHU'],
            ['nombre'=>'DIRECCION DE ASUNTOS JURIDICOS','codigo'=>'DIASJU'],
            ['nombre'=>'DIRECCION DE DESARROLLO ORGANIZACIONAL','codigo'=>'DIDEOR'],
            ['nombre'=>'DIRECCION RECURSOS HUMANOS','codigo'=>'DIREHU'],
            ['nombre'=>'DIRECCION COMUNICACION','codigo'=>'DICO'],
            ['nombre'=>'DIRECCION PLANIFICACION INTEGRAL','codigo'=>'DIPLIN'],
            ['nombre'=>'DIRECCION TRIBUTARIA Y RECAUDACIONES','codigo'=>'DITRRE'],
            ['nombre'=>'DIRECCION TESORO','codigo'=>'DITE'],
            ['nombre'=>'DIRECCION OBRAS Y DESARROLLO URBANO','codigo'=>'DIOBDEUR'],
            ['nombre'=>'DIRECCION DESARROLLO SOCIAL','codigo'=>'DIDESO'],
            ['nombre'=>'DIRECCION D.I.E.S.D.','codigo'=>'DIDIESD'],
            ['nombre'=>'DIRECCION EJECUTORA DE ASFALTO','codigo'=>'DIEJAS'],
            ['nombre'=>'DIRECCION MOV. URBANA TRAFICO Y VIALIDAD','codigo'=>'DIURTRVI'],
            ['nombre'=>'DIRECCION ORDENAMIENTO TERRITORIAL','codigo'=>'DIORTE'],
            ['nombre'=>'DIRECCION DESARROLLO ECONOMICO SOCIAL','codigo'=>'DIDEECSO'],
            ['nombre'=>'DIRECCION GESTION AMBIENTAL','codigo'=>'DIGEAM'],
            ['nombre'=>'DIRECCION DE IGUALDAD DE OPORTUNIDADES','codigo'=>'DIIGOP'],
            ['nombre'=>'DIRECCION PATRIMONIO Y TURISMO','codigo'=>'DIPATU'],
            ['nombre'=>'UNIDAD DE SISTEMAS','codigo'=>'UNSIS'],
            ['nombre'=>'UNIDAD DE CONTABILIDAD','codigo'=>'INCON'],
            ['nombre'=>'UNIDAD DE TRANSPARENCIA','codigo'=>'UNTR'],
            ['nombre'=>'UNIDAD DE TESORERIA','codigo'=>'UNTE'],
            ['nombre'=>'UNIDAD CONTROL URBANO','codigo'=>'UNCOUR'],
            ['nombre'=>'UNIDAD PAISAJISMO Y FORESTACION','codigo'=>'UNPAFO'],
            ['nombre'=>'UNIDAD DE SALUD','codigo'=>'UNSA'],
            ['nombre'=>'UNIDAD DE PRESUPUESTOS','codigo'=>'UNPR'],
            ['nombre'=>'UNIDAD BIENES Y SERVICIOS','codigo'=>'UNBISE'],
            ['nombre'=>'UNIDAD ADQUISICIONES','codigo'=>'UNAD'],
            ['nombre'=>'UNIDAD CATASTRO URBANO','codigo'=>'UNCAUR'],
            ['nombre'=>'UNIDAD DE EDUCACION','codigo'=>'UNED'],
            ['nombre'=>'UNIDAD SEGURIDAD CIUDADANA','codigo'=>'UNSECI'],
            ['nombre'=>'UNIDAD DEFENSA AL CONSUMIDOR','codigo'=>'UNDECON'],
            ['nombre'=>'UNIDAD FISCALIZACION','codigo'=>'UNFIS'],
            ['nombre'=>'UNIDAD GESTION DE PROYECTOSS','codigo'=>'UNGEPRO'],
            ['nombre'=>'UNIDAD MANTENIMIENTO DE VIAS','codigo'=>'UNMAVIA'],
            ['nombre'=>'UNIDAD P.A.N.','codigo'=>'UNPAN'],
            ['nombre'=>'UNIDAD MERCADOS','codigo'=>'UNME'],
            ['nombre'=>'UNIDAD DRENAJE URBANO','codigo'=>'UNDRUR'],
            ['nombre'=>'UNIDAD FISCALIZACION DE OBRAS','codigo'=>'UNFIOB'],
            ['nombre'=>'UNIDAD PREVENCION DE RIESGOS','codigo'=>'UNPRRI'],
            ['nombre'=>'UNIDAD PROM.ACT.FISICA Y DEPORTIVA','codigo'=>'UNPROFIDE'],
            ['nombre'=>'ADM.PARQUE ECOLOGICO','codigo'=>'ADMPAECO'],
            ['nombre'=>'ADM.PARQUE INTI RAYMI','codigo'=>'ADMPAINRA'],
            ['nombre'=>'ADM.AGUAS DANZANTES','codigo'=>'ADMAGDA'],
            ['nombre'=>'ADM.BALNEARIO CAPACHOS','codigo'=>'ADMBACA'],
            ['nombre'=>'ADM.SALON LUIS RAMIRO BELTRAN','codigo'=>'ADMSALURABE'],
            ['nombre'=>'ADM.MONUMENTO A LA VIRGEN','codigo'=>'ADMMOVI'],
            ['nombre'=>'ADM.CASA DE LA CULTURA','codigo'=>'ADMCACU'],
            ['nombre'=>'ADM.ZOOLOGICO','codigo'=>'ADMZOOLO'],
            ['nombre'=>'ADM.ZOONOSIS','codigo'=>'ADMZOONO'],
            ['nombre'=>'ADM.PISINA OLIMPICA','codigo'=>'ADMPIOL'],
            ['nombre'=>'ADM.MUSEO ILDEFONSO MURGUIA','codigo'=>'ADMMUIDMU'],
            ['nombre'=>'RESPONSABLE SEGURO UNIVERSAL DE SALUD','codigo'=>'RESEUNSA'],
            ['nombre'=>'JEFATURA CONTROL AMBIENTAL','codigo'=>'JECOAM'],
            ['nombre'=>'RESP.TALLER TEXTIL','codigo'=>'RETATE'],
            ['nombre'=>'UNID.ALUMBRADO PUBLICO','codigo'=>'UNALPU'],
            ['nombre'=>'UNID.ARCHIVO CENTRAL','codigo'=>'UNARCE'],
        ]);
    }
}
