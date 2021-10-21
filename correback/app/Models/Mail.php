<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;
    protected $fillable=[
        "codigo",
        "tipo",
        "tipo2",
        "ref",
        "fecha",
        "remitente",
        "cargo",
        "institucion",
        "fechacarta",
        "estado",
        "folio",
        "archivo",
        "codinterno",
        "codexterno",
        'user_id',
        'unit_id',
        'mail_id',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function mail(){
        return $this->belongsTo(Mail::class);
    }
    public function logs(){
        return $this->hasMany(Log::class);
    }
}
