<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Mail;
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
        $log= new Log();
        $log->mail_id=$request->mail_id;
        $log->user_id=$request->user()->id;
        $log->user_id2=$request->user_id2;
        $log->remitente=$request->user()->name;
        $log->destinatario=$request->destinatario;
        $log->accion=$request->accion;
        $log->estado='EN PROCESO';
        $log->fecha=date('Y-m-d');
        $log->hora=date('H:i:s');
        $log->unit_id=$request->unit_id;
        $log->save();
        $mail=Mail::find($request->mail_id);
        $mail->unit_id=$request->unit_id;
        $mail->user_id=$request->user_id2;
        $mail->save();
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
