<?php

namespace App\Http\Controllers;

use App\Models\Asigna;
use App\Models\Log;
use App\Models\Mail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

//use Barryvdh\DomPDF\Facade as PDF;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        return Mail::where('unit_id',$request->user()->unit_id)->get();
        return Mail::with('logs')
//            ->where('unit_id',$request->user()->unit_id)
//            ->where('estado','EN PROCESO')
//            ->orWhere('estado','ACEPTADO')
            ->whereRaw("estado in ('EN PROCESO','ACEPTADO')  AND user_id=?",[$request->user()->id])
            ->orderBy('id','DESC')
            ->get();
    }
    public function todos()
    {
        return Mail::with('logs')
            ->get();
    }
    public function buscar(Request $request)
    {
//        return Mail::where('unit_id',$request->user()->unit_id)->get();
        return Mail::where('codigo',$request->codigo)->with('logs')->get();
    }
    public function micorre(Request $request)
    {
//        return Mail::where('unit_id',$request->user()->unit_id)->get();
        //return Mail::with('logs')
//            ->where('estado','EN PROCESO')
//            ->orWhere('estado','ACEPTADO')
        //    ->where('user_id',$request->user()->id)
        //    ->whereRaw("estado in ('EN PROCESO','ACEPTADO')  AND user_id=?",[$request->user()->id])
         //   ->orderBy('id','DESC')
          //  ->get();

//        return Asigna::with('mail')
//        ->whereRaw("estado in ('EN PROCESO','ACEPTADO')  AND user_id=".$request->user()->id)
//        ->orderBy('id','DESC')
//        ->get();
//        return Log::where('user_id2',$request->user()->id)->with('mail')->with('user')->with('user2')->get();
        return Log::where('user_id2',$request->user()->id)->with('mail')->with('user')->with('user2')->orderBy('id','desc')->get();
    }


    public function dividir(Request $request)
    {
        $mail=Mail::find($request->mail_id);
        for ($i=1;$i<=$request->cantidad;$i++){
            $m=new Mail();
            $m->codigo=$mail->codigo.".".$i;
            $m->tipo=$mail->tipo;
            $m->tipo2="DIVISION";
            $m->ref=$mail->ref;
            $m->fecha=$mail->fecha;
            $m->fechacarta=$mail->fechacarta;
            $m->remitente=$mail->remitente;
            $m->cargo=$mail->cargo;
            $m->institucion=$mail->institucion;
            $m->estado=$mail->estado;
            $m->folio=$mail->folio;
            $m->archivo=$mail->archivo;
            $m->codinterno=$mail->codinterno;
            $m->codexterno=$mail->codexterno;
            $m->user_id=$mail->user_id;
            $m->unit_id=$mail->unit_id;
            $m->mail_id=$mail->id;
            $m->save();
            $log=new Log();
            $log->mail_id=$m->id;
            $log->user_id=null;
            $log->user_id2=$request->user()->id;
            $log->remitente='';
            $log->destinatario=$request->user()->name;
//        $log->estado=$request->estado;
            $log->fecha=date('Y-m-d');
            $log->hora=date('H:i:s');
            $log->unit_id=$request->user()->unit_id;
            $log->save();
        }
//        return Mail::where('unit_id',$request->user()->unit_id)->get();
//        return Mail::with('logs')->where('unit_id',$request->user()->unit_id)->where('estado','EN PROCESO')->get();
    }

    public function upload(Request $request){
        $this->validate($request, [
            'imagen'=>'required',
            'mail_id'=>'required'
        ]);
        if ($request->hasFile('imagen')) {
            $file=$request->file('imagen');
            $nombreArchivo = time().".".$file->getClientOriginalExtension();
            $file->move(\public_path('imagenes'), $nombreArchivo);
            $mail=Mail::find($request->mail_id);
            $mail->archivo=$nombreArchivo;
            $mail->save();
            return $nombreArchivo;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Mail::select('remitente','cargo','institucion')->groupBy('remitente','cargo','institucion')->get();
    }
    public function consulta(Request $request)
    {
        return Mail::
            where('userorigen_id',$request->user()->id)
            ->where('unitorigen_id',$request->user()->unit_id)
            ->whereRaw('fecha>=? AND fecha<=? ',[$request->fecha1,$request->fecha2])
            ->with('logs')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Mail::whereYear('created_at',date('Y'))->where('unit_id',$request->user()->unit_id)->max("codinterno")==''){
            $codigointerno=1;
        }else{
            $codigointerno=Mail::whereYear('created_at',date('Y'))->where('unit_id',$request->user()->unit_id)->max("codinterno")+1;
        }
//        return Mail::max("codinterno");
        $user=User::where('id',$request->user()->id)->with('unit')->get();
//        return $user[0]->unit->codigo;
//        return $request;
        $query=Mail::where('remitente',strtoupper( $request->remitente));
//        return  $query->count();
        if ($query->count()>0){
         DB::table('mails')->where('remitente',strtoupper( $request->remitente))->update([
             'cargo'=>strtoupper($request->cargo),
             'institucion'=>strtoupper($request->institucion),
         ]);
        }
//        return 'a';

        $mail=new Mail();
        $mail->codigo=$user[0]->unit->codigo.str_pad($codigointerno, 4, '0', STR_PAD_LEFT).'/'.date('y');
        $mail->tipo=$request->tipo;
//        $mail->tipo2=$request->tipo2;
        $mail->remitente= strtoupper( $request->remitente);
        $mail->cargo=strtoupper($request->cargo);
        $mail->institucion=strtoupper($request->institucion);
        $mail->ref=strtoupper($request->ref);
        $mail->fecha=date('Y-m-d');
        $mail->hora=date('H:i:s');
        $mail->fechacarta=$request->fecha;
//        $mail->estado=$request->estado;
        $mail->folio=$request->folio;
//        $mail->archivo=$request->archivo;
        $mail->codinterno=$codigointerno;
        $mail->codexterno=$request->codexterno;
        $mail->user_id=$request->user()->id;
        $mail->unit_id=$request->user()->unit_id;
//        $mail->userorigen_id=$request->user()->id;
//        $mail->unitorigen_id=$request->user()->unit_id;
//        $mail->mail_id=$request->mail_id;
        $mail->save();
        $log=new Log();
        $log->mail_id=$mail->id;
        $log->user_id=null;
        $log->user_id2=$request->user()->id;
//        $log->remitente='';
//        $log->destinatario=$request->user()->name;
//        $log->estado=$request->estado;
        $log->fecha=date('Y-m-d');
        $log->hora=date('H:i:s');
//        $log->unit_id=$request->user()->unit_id;
        $log->save();

        return $mail;
    }
    public function updatemail(Request $request){
        $mail=Mail::find($request->id);
        $mail->tipo = $request->tipo;
        $mail->ref = $request->ref;
        $mail->fechacarta= $request->fechacarta;
        $mail->folio= $request->folio;
        $mail->remitente= strtoupper( $request->remitente);
        $mail->cargo=strtoupper($request->cargo);
        $mail->institucion=strtoupper($request->institucion);
        $mail->codinterno=$request->codinterno;
        $mail->save();
        return $mail;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function show(Mail $mail,User $user)
    {
//        return $user;
        $pdf = App::make('dompdf.wrapper');
//        $customPaper = array(0,0,360,360);
        $pdf->setPaper('letter','landscape');
        if ($user)
//        return $request->user();
        $pdf->loadHTML('
                <style>
.tablex , th , .tdx  {
  border: 0.2px solid black;
  border-collapse: collapse;
}
*{
padding: 0px;
margin: 0px;
border: 0px;
font-size: 13px;
    font-family: Elegance, sans-serif;
}
</style>
<table style="width: 100%">
<tr>
    <td style="width: 50%;"></td>
    <td style="padding: 25px">
        <table style="width: 100%;">
        <tr><td align="center" style="font-weight: bold;font-size: 17px">GOBIERNO AUTÓNOMO MUNICIPAL DE ORURO</td></tr>
        </table>
        <table style="width: 100%;padding-top: 15px">
        <tr>
            <td style="width: 34%;font-weight:bold;font-size: 30px" align="center">HOJA DE TRAMITE</td>
            <td style="width: 66%">
            <table style="width: 100%" >
                <tr>
                <td style="width: 50%;font-weight:bold;font-size: 15px" align="right">Registro No.: </td>
                <td style="border-bottom: 1px solid #6b7280" align="center">'.$mail->codinterno.'</td>
                </tr>
                <tr>
                <td style="width: 50%;font-weight:bold;font-size: 15px" align="right">No. Hojas: </td>
                <td style="border-bottom: 1px solid #6b7280" align="center">'.$mail->folio.'</td>
                </tr>
                <tr>
                <td style="width: 50%;font-weight:bold;font-size: 15px" align="right"> Fecha de Ingreso: </td>
                <td style="border-bottom: 1px solid #6b7280" align="center">'.$mail->fecha.'</td>
                </tr>
                <tr>
                <td style="width: 50%;font-weight:bold;font-size: 15px" align="right">Fecha de Salida: </td>
                <td style="border-bottom: 1px solid #6b7280" align="center"> </td>
                </tr>
            </table>
            </td>
        </tr>
        </table>
        <table style="width: 100%;padding-top: 5px">
        <tr>
            <td style="width: 17%;font-weight:bold">Procedencia:</td>
            <td style="width: 83%;border-bottom: 1px solid #6b7280" >'.$mail->remitente.'</td>
        </tr>
        <tr>
            <td style="width: 17%;font-weight:bold">Referencia:</td>
            <td style="width: 83%;border-bottom: 1px solid #6b7280" >'.$mail->ref.'</td>
        </tr>
        </table>
        <table style="width: 100%;border: 1px solid black;border-collapse: collapse">
        <tr >
        <th style="width: 40%">DESTINO</th><th style="width: 5%">DE</th><th style="width: 5%">A</th><th style="width: 30%">INSTRUCCIONES</th><th style="width: 10%"></th>
        </tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Stria. Mcpal de Econ. y Hacienda</td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"> Urgente</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Dirección de Trib. y Recaud. </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Informe en el día</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Dirección del Tesoro Municipal </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Reúna antecedentes</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Bienes y servicios </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Remita antecedentes</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Dirección Desarr. Econ. Local </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Instruya su ejecución</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Defensa del Consumidor </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Archive</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Mercados </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Conteste Carta</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Actividades Económicas </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Verificar y procesar</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Espectáculos Públ. y Publ. Urbana </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Efectúe liquidación</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Ventanilla Única </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Remitase a la MAE</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Valores Municipales </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Informe</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Asistente </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px">Su Atención</td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px">Secretaría</td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px;color: white">A </td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td><td style="border: 0.2px solid black;padding-left: 2px"></td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px;font-weight: bold" colspan="5">Para:</td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px;font-weight: bold" colspan="5">Instrucciones Complementarias:</td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px;color: white" colspan="5">A </td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px;color: white" colspan="5">A </td></tr>
        <tr>
        <td style="border: 0.2px solid black;padding-left: 2px;font-weight: bold" colspan="5">
        <table style="width: 100%">
            <tr>
            <td style=";width: 50%;"></td>
            <td style="width: 50%;" align="center"> <div style="border-top: 1px solid black;margin-top: 40px">FIRMA Y SELLO</div></td>
            </tr>
        </table>
        Para:
        </td>
        </tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px;font-weight: bold" colspan="5">Instrucciones Complementarias:</td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px;color: white" colspan="5">A </td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px;color: white" colspan="5">A </td></tr>
        <tr><td style="border: 0.2px solid black;padding-left: 2px;font-weight: bold" colspan="5">
        <table style="width: 100%">
            <tr>
            <td style=";width: 50%;"></td>
            <td style="width: 50%;" align="center"> <div style="border-top: 1px solid black;margin-top: 40px">FIRMA Y SELLO</div></td>
            </tr>
        </table>
        Codigo:'.$mail->codigo.'
        </td></tr>
        </table>
    </td>
</tr>
</table>
','UTF-8');
        return $pdf->stream();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function edit(Mail $mail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mail $mail)
    {
        $mail->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mail=Mail::find($id);
        return $mail->delete();

    }

    public function anulado(Request $request){
        $mail=Log::find($request->mail_id);
        $mail->estado='ARCHIVADO';
        $mail->save();

//        $mail=Mail::find($request->mail_id);
//        $mail->estado='ANULADO';
//        $mail->save();
//
//        $log=new Log();
//        $log->mail_id=$request->mail_id;
//        $log->user_id=null;
//        $log->user_id2=$request->user()->id;
//        $log->remitente='';
//        $log->accion=$request->accion;
//        $log->destinatario=$request->user()->name;
//        $log->estado='ANULADO';
//        $log->fecha=date('Y-m-d');
//        $log->hora=date('H:i:s');
//        $log->unit_id=$request->user()->unit_id;
//        $log->save();

    }

    public function archivar(Request $request)
    {
        $mail=Mail::find($request->id);
        $mail->estado='ARCHIVADO';
        $mail->save();

    }
    public function aceptar(Request $request){
        $mail=Log::find($request->mail_id);
        $mail->estado='ACEPTADO';
        $mail->save();
    }

    public function impruta($id){
        $dato=Mail::find($id);
        $cadena="<html>
        <style>
        table, th, td {
            border: 1px solid black;
          }
          table{width:100%;
            border-collapse: collapse;}
            .titulo{
                text-align:center;
                font-weight: bold;
            }
            .subt{
                font-weight: bold;
            }
            .imagen{width:100px;
                height:100px;}
                *{
                    padding: 0px;
                    margin: 0px;
                    border: 0px;}
        </style>

        <body>
        <table>
        <tr><td rowspan=2><img class='imagen' src='img/escudo.png'></td><td class='titulo'>GOBIERNO AUTONOMO MUNICIPAL DE ORURO</td><td class='titulo'>Codigo Ruta</td></tr>
        <tr><td  class='titulo'>HOJA DE RUTA</td><td style='text-align:center'>".$dato->codigo."</td></tr>
        </table>
        <table>
        <tr><td class='subt'>DATOS DE RECEPCION:</td><td colspan='3'><b>Fecha y Hora:</b> ".date('Y-m-d H:i:s')."</td></tr>
        <tr><td class='subt'>DATOS DEL CITE:</td><td><b>Fecha:</b> ".$dato->fecha."</td><td><b>Cite:</b> ".$dato->codexterno."</td><td><b>Folio:</b> ".$dato->folio."</td></tr>
        <tr><td class='subt'>DATOS DEL REMITENTE:</td><td colspan=2><b>Nombre y apellido:</b> ".$dato->remitente."</td><td><b>Cargo/Institucion:</b> ".$dato->institucion."</td></tr>
        <tr><td class='subt'>DESCRIPCION DEL CONTENIDO:</td><td colspan=3><b>Referencias:</b> ".$dato->ref."</td></tr>
        </table>
            <p class='titulo' style='margin:0'>CORRESPONDENCIA : ".$dato->tipo."</p>
        <table><tr><td>

        <table>
            <tr ><td colspan='2' style=' font-weight: bold;'>Para:</td></tr>

            <tr><td style='font-weight: bold;'>Fecha Entrega:</td><td style='font-weight: bold;'>Firma:</td></tr>
            <tr><td class='titulo' colspan=2>INSTRUCCIONES / OBSERVACIONES</td><tr>
        <tr><td colspan=2 style='height:30px'> <br><hr><br><hr><br><hr><br><hr><br></td></tr>
        <tr><td style='font-weight: bold;'>Fecha Recibido:</td><td style='font-weight: bold;'>Firma:</td></tr>
        </table>
        </td><td>
        <table>
        <tr ><td colspan='2' style=' font-weight: bold;'>Para:</td></tr>

        <tr><td style='font-weight: bold;'>Fecha Entrega:</td><td style='font-weight: bold;'>Firma:</td></tr>
        <tr><td class='titulo' colspan=2>INSTRUCCIONES / OBSERVACIONES</td><tr>
        <tr><td colspan=2 style='height:30px'> <br><hr><br><hr><br><hr><br><hr><br></td></tr>
        <tr><td style='font-weight: bold;'>Fecha Recibido:</td><td style='font-weight: bold;'>Firma:</td></tr>

        </table>
        </td></tr></table>
        <br>
        <table><tr><td>

        <table>
            <tr ><td colspan='2' style=' font-weight: bold;'>Para:</td></tr>
            <tr><td style='font-weight: bold;'>Fecha Entrega:</td><td style='font-weight: bold;'>Firma:</td></tr>
            <tr><td class='titulo' colspan=2>INSTRUCCIONES / OBSERVACIONES</td><tr>
        <tr><td colspan=2 style='height:30px'> <br><hr><br><hr><br><hr><br><hr><br></td></tr>
        <tr><td style='font-weight: bold;'>Fecha Recibido:</td><td style='font-weight: bold;'>Firma:</td></tr>
        </table>
        </td><td>
        <table>
        <tr ><td colspan='2' style=' font-weight: bold;'>Para:</td></tr>
        <tr><td style='font-weight: bold;'>Fecha Entrega:</td><td style='font-weight: bold;'>Firma:</td></tr>
        <tr><td class='titulo' colspan=2>INSTRUCCIONES / OBSERVACIONES</td><tr>
        <tr><td colspan=2 style='height:30px'> <br><hr><br><hr><br><hr><br><hr><br></td></tr>
        <tr><td style='font-weight: bold;'>Fecha Recibido:</td><td style='font-weight: bold;'>Firma:</td></tr>

        </table>
        </td></tr></table>

        <br>
        <table><tr><td>

        <table>
            <tr ><td colspan='2' style=' font-weight: bold;'>Para:</td></tr>
            <tr><td style='font-weight: bold;'>Fecha Entrega:</td><td style='font-weight: bold;'>Firma:</td></tr>
            <tr><td class='titulo' colspan=2>INSTRUCCIONES / OBSERVACIONES</td><tr>
        <tr><td colspan=2 style='height:30px'> <br><hr><br><hr><br><hr><br><hr><br></td></tr>
        <tr><td style='font-weight: bold;'>Fecha Recibido:</td><td style='font-weight: bold;'>Firma:</td></tr>
        </table>
        </td><td>
        <table>
        <tr ><td colspan='2' style=' font-weight: bold;'>Para:</td></tr>
        <tr><td style='font-weight: bold;'>Fecha Entrega:</td><td style='font-weight: bold;'>Firma:</td></tr>
        <tr><td class='titulo' colspan=2>INSTRUCCIONES / OBSERVACIONES</td><tr>
        <tr><td colspan=2 style='height:30px'> <br><hr><br><hr><br><hr><br><hr><br></td></tr>
        <tr><td style='font-weight: bold;'>Fecha Recibido:</td><td style='font-weight: bold;'>Firma:</td></tr>

        </table>
        </td></tr></table>
        </body>
        </html>";
        return $cadena;
    }
}
