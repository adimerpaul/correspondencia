<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable=[
        'mail_id',
        'user_id',
        'user_id2',
        'remitente',
        'destinatario',
        'estado',
        'fecha',
        'hora',
        'accion',
        'archivado',
        'unid_id',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function user2(){
        return $this->belongsTo(User::class,'user_id2')->with('unit');
    }
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function mail(){
        return $this->belongsTo(Mail::class)->with('logs');
    }
}
