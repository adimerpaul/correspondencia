<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Mail;
use App\Models\Asigna;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return $request;
        foreach ($request->list_user as $list) {
        $log= new Log();
        $log->mail_id=$request->mail_id;
        $log->user_id=$request->user()->id;
        $log->user_id2=$list['id'];
        $log->remitente=$request->user()->name;
        $log->destinatario=$list['name'];
        $log->accion=$request->accion;
        $log->estado='EN PROCESO';
        $log->fecha=date('Y-m-d');
        $log->hora=date('H:i:s');
        $log->unit_id=$list['unit_id'];
        $log->save();

        $mail=Mail::find($request->mail_id);
        $mail->estado='ENVIADO';
        $mail->save();

        $asigna =new Asigna;
        $asigna->mail_id=$request->mail_id;
        $asigna->user_id=$list['id'];
        $asigna->unit_id=$list['unit_id'];
        $asigna->fecha_reg=date('Y-m-d H:i:s');
        $asigna->estado='EN PROCESO';
        $asigna->save();
    }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(Log $log)
    {
        //
    }
}
